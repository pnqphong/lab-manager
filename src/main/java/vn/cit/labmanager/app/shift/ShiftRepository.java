package vn.cit.labmanager.app.shift;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ShiftRepository extends JpaRepository<Shift, String> {

	public Shift findTopByOrderByModifiedDesc();

}
