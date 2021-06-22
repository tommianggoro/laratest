<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="col-md-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <h3>Dashboard</h3>
                </div>
                <div class="card-body">
                    <h5>Welcome dashboard, <strong>{{ Auth::user()->name }}</strong></h5>
                    <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div> 

        <div class="clear"></div>
        <br>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Job Positions</h3>
                </div>
                <div class="card-body">
                    <form class="form-inline">
                        <div class="form-group mb-2">
                            <label for="jobDesc" class="sr-only">Job Description</label>
                            <input type="text" class="form-control" id="jobDesc" placeholder="Search by description" name="job_desc" value="{{ $job_desc }}">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="jobLoc" class="sr-only">Job Location</label>
                            <input type="text" class="form-control" id="jobLoc" placeholder="Search By Location" name="job_location"  value="{{ $job_loc }}">
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="defaultCheck1" name="job_type" <?php echo !empty($job_type) ? 'checked="checked"' : ''; ?> >
                          <label class="form-check-label" for="defaultCheck1">
                            Full Time Only
                          </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                    @foreach($data as $job)
                        @if($job)
                            <div class="row">
                                <div class="col-md-9">
                                    <a href="detail/{{ $job->id }}">{{ $job->title }}</a>
                                    <br>
                                    {{ $job->company }} <font style="color: green; font-weight: bold">{{ $job->type }}</font>
                                </div>
                                <div class="col-md-3">
                                    {{ $job->location }}
                                    <br>
                                    {{ $job->created }}
                                </div>
                            </div>
                            <hr />
                        @endif
                    @endforeach
                    @if(!empty($prevLink))
                        <a href="{{ $prevLink }}" class="btn btn-info">Previous</a> 
                    @endif
                    <a href="{{ $nextLink }}" class="btn btn-info">Next</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>