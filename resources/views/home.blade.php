@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
                <?php if ($active_user['role'] == 'admin'){ ?>

                    <div class="card" style="padding: 20px;">
                        <h4>Question Management</h4>
                        <div class="row">
                            <div class="col">
                                <p>Add Question</p>
                                <form action="{{ URL::to('/add') }}" method="get">
                                    <input name="question_body" class="form-control" required
                                    style="resize: none; margin-bottom: 5px;" placeholder="e.g How do I check my tracking number?"/>
                                    Answer Type: <select name="answer_type" class="btn btn-default">
                                        <option value="text">Text</option>
                                        <option value="binary">Yes/No</option>
                                    </select>
                                    <input type="submit" value="New Question" class="btn btn-default">
                                    <a href="/csv" class="btn btn-dark" target="_blank">Make CSV</a>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <table class="table table-striped text-center" id="qTable">
                                    <tr>
                                        <th onclick="sortTable(0)">Question</th>
                                        <th onclick="sortTable(0)">User Answer</th>
                                        <th onclick="sortTable(0)">Confirmed Status</th>
                                        <th onclick="sortTable(0)">User Name</th>
                                        <th>Operation</th>
                                    </tr>
                                    <?php foreach($questions as $q){ ?>
                                        <tr>
                                            <form action="{{ URL::to('/edit') }}" method="get">
                                            <td><input type="text" name="body" value="{{ $q['query_body'] }}" class="form-control"/></td>
                                            <?php if ($q['query_answer'] == 'text'){ ?>
                                                <td><input type="text" name="answer" value="{{ (!empty($q['query_answer'])) ? $q['query_answer'] : 'Not available' }}" class="form-control"/></td>
                                            <?php } else { ?>
                                                <td>
                                                    <select name="answer" class="form-control">
                                                        <option value="yes">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </td>
                                            <?php } ?>
                                            <td><input type="text" name="status" value="{{ (!empty($q['query_status'])) ? $q['query_status'] : 'Not available' }}" class="form-control"/></td>
                                            <td><input type="text" name="username" value="{{ (!empty($q['query_username'])) ? $q['query_username'] : 'Not available' }}" class="form-control"/></td>
                                            <td>
                                                <input type="hidden" name="qid" value="{{ $q['query_id'] }}"/>
                                                <input type="submit" value="Save" class="btn btn-primary form-control" name="edit">
                                                </form><!-- end of edit -->
                                                <form action="{{ URL::to('/remove') }}" method="get">
                                                    <input type="hidden" name="qid" value="{{ $q['query_id'] }}"/>
                                                    <input type="submit" value="Remove" class="btn btn-danger form-control"  name="delete">
                                                </form><!-- end of delete -->
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php } else if ($active_user['role'] == 'mod'){ ?>

                    <div class="card" style="padding: 20px;">
                        <h4>Question Requests</h4>
                        <table class="table table-striped" id="qTable">
                        <tr>
                            <th onclick="sortTable(0)">Question</th>
                            <th onclick="sortTable(0)">Answer</th>
                            <th onclick="sortTable(0)">User</th>
                            <th onclick="sortTable(0)">Status</th>
                            <th>Operation</th>
                        </tr>
                        <?php foreach($questions as $q){ ?>
                            <tr>
                                <td>{{ $q['query_body'] }}</td>
                                <td>{{ (!empty($q['query_answer'])) ? $q['query_answer'] : 'No answer given yet.' }}</td>
                                <td>{{ (!empty($q['query_username'])) ? $q['query_username'] : 'No user answered yet.' }}</td>
                                <td>{{ (!empty($q['query_status'])) ? $q['query_status'] : 'Status pending at the moment.' }}</td>
                                <td>
                                        <?php if ($q['query_status'] == 'Pending'){ ?>
                                                <a href="/approve/{{ $q['query_id'] }}" class="btn btn-success">Approve</a>
                                                <a href="/reject/{{ $q['query_id'] }}" class="btn btn-danger">Reject</a>
                                            </form>
                                        <?php } else if ($q['query_status'] == 'approved') { ?>
                                            <span style="color: green;">Approved</span>
                                        <?php } ?>
                                </td>
                                </form>
                            </tr>
                        <?php } ?>

                        
                    </div>   
                
                <?php } else if ($active_user['role'] == 'user'){ ?>
      
                    <div class="card" style="padding: 20px;">
                        <h4>Question Answers</h4>
                        <table class="table table-striped" id="qTable">
                        <tr>
                            <th onclick="sortTable(0)">Question</th>
                            <th onclick="sortTable(0)">Answer</th>
                            <th>Operation</th>
                        </tr>
                        <?php foreach($questions as $q){ ?>
                            <tr>
                                <form action="{{ URL::to('/uedit') }}" method="get">
                                    <td>{{ $q['query_body'] }}</td>
                                    <td>{{ ($q['query_answer'] != 'binary' || $q['query_answer'] != 'text') ? $q['query_answer'] : '' }}        
                                        <?php if ($q['query_status'] == 'Pending'){ ?>
                                            <span style="color: red;">Pending</span>
                                        <?php } else if ($q['query_status'] == 'Approved') { ?>
                                            <span style="color: green;">Approved</span>
                                        <?php } else if ($q['query_status'] == 'Rejected') { ?>
                                            <span style="color: red;">Rejected</span>
                                        <?php } else { ?>
                                            <?php if ($q['query_answer'] == 'text'){ ?>
                                                <input type="text" name="uanswer" class="form-control"/>
                                            <?php } else if ($q['query_answer'] == 'binary') { ?>
                                                <select name="uanswer" class="form-control">
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($q['query_status'] == 'Pending'){ ?>
                                            <span style="color: green;">Sent</span>
                                        <?php } else if ($q['query_status'] == 'Approved') { ?>
                                            <span>Can't answer.</span>
                                        <?php } else if ($q['query_status'] == 'Rejected') { ?>
                                            <span>Can't answer.</span>
                                        <?php } else { ?>
                                            <input type="hidden" name="qid" value="{{ $q['query_id'] }}"/>
                                            <input type="hidden" name="uid" value="{{ $active_user['id'] }}"/>
                                            <input type="submit" value="Answer" class="form-control" name="answer">
                                        <?php } ?>
                                    </td>
                                </form>
                            </tr>
                        <?php } ?>

                    </div>
                
                <?php } ?>
    
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById('qTable');
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>
@parent

@endsection