package vn.cit.labmanager.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
public class LoginController {

	@RequestMapping(path = "/login")
    public String processView() {
        return "login";
    }

}
