package vn.cit.labmanager.app.course;

import java.time.format.DateTimeFormatter;
import java.util.List;
import java.util.Optional;

import org.apache.commons.lang3.StringUtils;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Sort;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;

import vn.cit.labmanager.app.period.Period;
import vn.cit.labmanager.app.period.PeriodService;
import vn.cit.labmanager.app.subject.SubjectService;
import vn.cit.labmanager.app.user.UserService;

@Controller
public class CourseAdminController {

	@Autowired
	private CourseService service;
	
	@Autowired
	private SubjectService subjectService;
	
	@Autowired
	private UserService userService;
	
	@Autowired
	private PeriodService periodService;
	
	@RequestMapping(path = "/admin/courses")
    public String index(Model model) {
		Optional<Course> lastModifiedCourse = service.findTopByOrderByModifiedDesc();
		String lastModification = lastModifiedCourse.map(Course::getModified)
				.map(modified -> modified.format(DateTimeFormatter.ofPattern("hh':'mm a',' dd/MM/yyyy")))
				.orElse(StringUtils.EMPTY);
		
		model.addAttribute("courses", service.findAll());
		model.addAttribute("lastModification", lastModification);
        return "admin/course/index";
    }
	
	@RequestMapping(path = "/admin/courses", method = RequestMethod.POST)
    public String saveCourse(Course course) {
		service.save(course);
		return "redirect:/admin/courses";
    }
	
	@RequestMapping(path = "/admin/courses/add")
    public String createCourse(Model model) {
		List<Period> periods = periodService.findAvailablePeriod(new Sort(Sort.Direction.ASC, "startDate"));
		model.addAttribute("existAvailablePeriod", !periods.isEmpty());
		if (!periods.isEmpty()) {
			model.addAttribute("course", new Course());
	        model.addAttribute("subjects", subjectService.findAll());
	        model.addAttribute("users", userService.findAll());
	        model.addAttribute("periods", periods);
		}
        return "admin/course/edit";   
    }
	
	@RequestMapping(path = "/admin/courses/edit/{id}")
    public String editCourse(@PathVariable(name = "id") String id, Model model) {
		List<Period> periods = periodService.findAvailablePeriod(new Sort(Sort.Direction.ASC, "startDate"));
		model.addAttribute("existAvailablePeriod", !periods.isEmpty());
		if (!periods.isEmpty()) {
			Optional<Course> course = service.findOne(id);
	        course.ifPresent(item -> {
	        	model.addAttribute("course", item);
	        	model.addAttribute("subjects", subjectService.findAll());
	            model.addAttribute("users", userService.findAll());
	            model.addAttribute("periods", periods);
	        });
		}
        return "admin/course/edit";   
    }
	
	@RequestMapping(path = "/admin/courses/delete/{id}")
    public String deleteCourse(@PathVariable(name = "id") String id) {
        service.delete(id);
        return "redirect:/admin/courses";   
    }

}
