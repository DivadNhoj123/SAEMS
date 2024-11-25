<div class="modal fade" id="update-event-modal-<?= $row['id'] ?>" tabindex="-1" aria-labelledby="updateEventLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateEventLabel">Update Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="update_event.php" method="POST">
                    <!-- Hidden field to store event ID -->
                    <input type="hidden" name="event_id" value="<?= $row['id'] ?>">
                    <div class="row">
                        <div class="col-md-6 pe-0">
                            <div class="form-group form-group-default">
                                <label>Event Name</label>
                                <input type="text" name="event_name" class="form-control" value="<?= htmlspecialchars($row['event_name']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Status</label>
                                <input type="text" name="status" class="form-control" value="<?= htmlspecialchars($row['status']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6 pe-0">
                            <div class="form-group form-group-default">
                                <label>Location</label>
                                <input type="text" name="event_location" class="form-control" value="<?= htmlspecialchars($row['event_location']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Event Date</label>
                                <input type="date" name="event_schedule" class="form-control" value="<?= htmlspecialchars($row['event_schedule']) ?>" required>
                            </div>
                        </div>
                        <input type="hidden" name="student_id" value="<?= isset($_SESSION['student_id']) ? $_SESSION['student_id'] : '' ?>" />
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" name="update_event" class="btn btn-primary">Update</button>
                        <button type="button" id="alert_demo_8" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>