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
            <div class="card inline">
                <div class="card-header">
                    <h3>Job Detail</h3>
                    <a href="{{ route('home') }}" class="btn btn-danger float-right">Back to Home</a>
                </div>
                <div class="card-body">
                    {{ $data['type'] }} / {{ $data['location'] }}
                    <br>
                    <h3>{{ $data['title'] }}</h3>
                    <hr>

                    <br>
                    <div class="row">
                        <div class="col-md-9">
                            <?php echo $data['description']; ?>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    {{ $data['company'] }}
                                </div>
                                <div class="card-body">
                                    <img src="<?php echo $data['company_logo']; ?>" width=200 height=100/>
                                    <a href="{{ $data['company_url'] }}">{{ $data['company_url'] }}</a>
                                </div>
                            </div>
                            <br>
                            <div class="card">
                                <div class="card-header">
                                    How to Aplly
                                </div>
                                <div class="card-body">
                                    <?php echo $data['how_to_apply'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</body>
</html>