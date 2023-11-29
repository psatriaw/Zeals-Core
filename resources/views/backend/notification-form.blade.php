<!doctype html>
<html lang="en">
        <head>
        <!-- Required meta tags -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

                <!-- Bootstrap CSS -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

                <title>Notification FCM</title>
        </head>
        <body>
                {{-- @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege)) --}}

                <div class="container">
                        <form method="POST" action="../submit-fcm">
                                @csrf
                                <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title">
                                </div>
                                <div class="form-group">
                                        <label for="message">Message</label>
                                        <input type="text" class="form-control" id="message" name="message">
                                </div>
                                <div class="form-group">
                                        <label for="status">Type</label>
                                        <select class="form-control" aria-label="select type" id="status" name="status">
                                                <option selected>SELECT TYPE</option>
                                                <option value="DRAFT">DRAFT</option>
                                                <option value="SCHEDULED">SCHEDULED</option>
                                                <option value="COMPLETED">COMPLETED</option>
                                        </select>
                                </div>
                                <div class="form-group">
                                        <label for="link">Link (include only the /campaign/detail/id)</label>
                                        <input type="text" class="form-control" id="link" name="link">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show mt-3">
                                        {{ session('success') }}
                                </div>
                        @endif
                </div>
        </body>
</html>