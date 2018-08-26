package vn.cit.labmanager.restcontroller;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;

import vn.cit.labmanager.domain.Shift;
import vn.cit.labmanager.service.ShiftService;

@RestController
public class ShiftRestController {
	
	@Autowired
	private ShiftService service;
	
	@GetMapping("/shifts")
	public List<Shift> findAll() {
		return service.findAll();
	}
}