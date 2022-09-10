<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>SSMS</title>
      </head>
      <body>
        <table class="table">
      <thead>
                  <tr>
                    <th> First Name </th>
                    <th> Last Name </th>
                    <th> Subject </th>
                    <th> Participation </th>
                    <th> Mid Term </th>
                    <th> Final Term </th>
                    <th> Total </th>
                    <th> Performance </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($get_data as $get_data1)
                  <tr class="odd gradeX">
                    <td>{{$get_data1->first_name}}</td>
                    <td class="left">{{$get_data1->last_name}}</td>
                    <td class="left">{{$get_data1->subject_name}}</td>
                    <td class="left">{{$get_data1->student_participation}}</td>
                    <td class="left">{{$get_data1->student_midterm}}</td>
                    <td class="left">{{$get_data1->student_final}}</td>
                    <td class="left">{{$get_data1->student_term}}</td>
                    <td class="left">{{$get_data1->grade}}</td>
                  </tr>
                  @endforeach
                </tbody>
      </table>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>