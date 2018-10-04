package vn.cit.labmanager.tool;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ToolRepository extends JpaRepository<Tool, String> {

	public Tool findTopByOrderByModifiedDesc();

}
