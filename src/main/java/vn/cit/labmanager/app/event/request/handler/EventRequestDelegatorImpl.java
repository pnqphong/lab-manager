package vn.cit.labmanager.app.event.request.handler;

import java.time.LocalDate;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.Set;
import java.util.stream.Collectors;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import vn.cit.labmanager.app.event.Event;
import vn.cit.labmanager.app.event.EventService;
import vn.cit.labmanager.app.event.request.EventRequest;
import vn.cit.labmanager.app.event.request.EventRequestInitializingService;
import vn.cit.labmanager.app.event.request.EventRequestService;
import vn.cit.labmanager.app.event.request.form.EventRequestForm;
import vn.cit.labmanager.app.lab.Lab;
import vn.cit.labmanager.app.lab.LabService;
import vn.cit.labmanager.app.shift.Shift;
import vn.cit.labmanager.app.tool.Tool;

@Service
public class EventRequestDelegatorImpl implements EventRequestDelegator {

	@Autowired
	private EventRequestInitializingService initService;
	
	@Autowired
	private LabService labService;
	
	@Autowired
	private EventService eventService;
	
	@Autowired
	private EventRequestService requestService;
	
	@Autowired
	private AdditionalEventRequestBuilder builder;
	
	@Override
	public void delegate(EventRequestForm requestForm) {
		List<EventRequest> requests = initService.from(requestForm);
		
		// Get labs
		List<Lab> labs = labService.findAll();
		
		List<EventRequest> pendingRequests = new ArrayList<>();
		
		requests.forEach(request -> {
			List<EventRequest> completeRequests = builder.build(request);
			if (completeRequests.size() > 1) {
				boolean successAtLeastOne = false;
				for(EventRequest completeRequest : completeRequests) {
					List<Lab> availableLabs = new ArrayList<>(labs);
					availableLabs.removeAll(getLabsHaveBeenRegistered(availableLabs, completeRequest.getStartDate(), completeRequest.getShift()));
					availableLabs.removeAll(getLabNotEnoughTool(availableLabs, completeRequest.getTools()));
					if (!availableLabs.isEmpty()) {
						completeRequest.setLab(availableLabs.get(0));
						eventService.save(Event.from(completeRequest));
						successAtLeastOne = true;
						break;
					}
				}
				if (!successAtLeastOne) {
					pendingRequests.add(request);
				}
			} else {
				List<Lab> availableLabs = new ArrayList<>(labs);
				availableLabs.removeAll(getLabsHaveBeenRegistered(availableLabs, request.getStartDate(), request.getShift()));
				availableLabs.removeAll(getLabNotEnoughTool(availableLabs, request.getTools()));
				if (!availableLabs.isEmpty()) {
					request.setLab(availableLabs.get(0));
					eventService.save(Event.from(request));
				} else {
					pendingRequests.add(request);
				}
			}
		});

		requestService.save(pendingRequests);
	}
	
	private List<Lab> getLabsHaveBeenRegistered(List<Lab> lookUpLabs, LocalDate date, Shift shift) {
		List<Event> registeredEvents = eventService.findByLabInAndStartDateEqualsAndShiftEquals(lookUpLabs, date, shift);
		return registeredEvents.stream().map(Event::getLab).collect(Collectors.toList());
	}
	
	private List<Lab> getLabNotEnoughTool(List<Lab> lookUpLabs, Set<Tool> neededTools) {
		if (neededTools.isEmpty()) {
			return Collections.emptyList();
		}
		return lookUpLabs.stream().filter(lab -> !lab.getTools().containsAll(neededTools)).collect(Collectors.toList());
	}

	@Override
	public List<Lab> getAvailableLab(EventRequest request) {
		// Get labs
		List<Lab> labs = labService.findAll();
		List<EventRequest> completeRequests = builder.build(request);
		if (completeRequests.size() > 1) {
			for(EventRequest completeRequest : completeRequests) {
				List<Lab> availableLabs = new ArrayList<>(labs);
				availableLabs.removeAll(getLabsHaveBeenRegistered(availableLabs, completeRequest.getStartDate(), completeRequest.getShift()));
				availableLabs.removeAll(getLabNotEnoughTool(availableLabs, completeRequest.getTools()));
				if (!availableLabs.isEmpty()) {
					return availableLabs;
				}
			}
		} else {
			List<Lab> availableLabs = new ArrayList<>(labs);
			availableLabs.removeAll(getLabsHaveBeenRegistered(availableLabs, request.getStartDate(), request.getShift()));
			availableLabs.removeAll(getLabNotEnoughTool(availableLabs, request.getTools()));
			if (!availableLabs.isEmpty()) {
				return availableLabs;
			}
		}
		return Collections.emptyList();
	}

	@Override
	public void delegate(EventRequest request) {
		// Get labs
		List<Lab> labs = labService.findAll();
		List<EventRequest> completeRequests = builder.build(request);
		if (completeRequests.size() > 1) {
			for(EventRequest completeRequest : completeRequests) {
				List<Lab> availableLabs = new ArrayList<>(labs);
				availableLabs.removeAll(getLabsHaveBeenRegistered(availableLabs, completeRequest.getStartDate(), completeRequest.getShift()));
				availableLabs.removeAll(getLabNotEnoughTool(availableLabs, completeRequest.getTools()));
				if (!availableLabs.isEmpty()) {
					completeRequest.setLab(availableLabs.get(0));
					eventService.save(Event.from(completeRequest));
					requestService.delete(request.getId());
					break;
				} else {
					requestService.save(request);
				}
			}
		} else {
			List<Lab> availableLabs = new ArrayList<>(labs);
			availableLabs.removeAll(getLabsHaveBeenRegistered(availableLabs, request.getStartDate(), request.getShift()));
			availableLabs.removeAll(getLabNotEnoughTool(availableLabs, request.getTools()));
			if (!availableLabs.isEmpty()) {
				request.setLab(availableLabs.get(0));
				eventService.save(Event.from(request));
				requestService.delete(request.getId());
			} else {
				requestService.save(request);
			}
		}
		
	}

}
