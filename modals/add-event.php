<!-- Modal -->
<div
    class="modal fade"
    id="addRowModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold"> Add</span>
                    <span class="fw-light"> Event </span>
                </h5>
                <button
                    type="button"
                    class="close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../admin/insert_event.php" method="POST">
                <div class="modal-body">
                    <p class="small">
                        Fill the textbox to add event!
                    </p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Event Name</label>
                                <input type="text" name="event_name" class="form-control" placeholder="Enter Event Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Event Type</label>
                                <select
                                    name="event_type" class="form-select" id="formGroupDefaultSelect">
                                    <option value="SCC Event">SCC Event</option>
                                    <option value="Org Event">Org Event</option>
                                    <option value="Campus Event">Campus Event</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Status</label>
                                <select name="status" class="form-select" id="formGroupDefaultSelect">
                                    <option>Ongoing</option>
                                    <option>Upcoming</option>
                                    <option>Done</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group form-group-default">
                                <label>Location</label>
                                <input type="text" name="event_location" class="form-control" placeholder="Enter Location" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Date</label>
                                <input type="date" name="event_schedule" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="student_id" value="<?= isset($_SESSION['student_id']) ? $_SESSION['student_id'] : '' ?>" />
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>