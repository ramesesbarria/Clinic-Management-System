<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <style>
            * {
                font-family: 'Merriweather', serif;
            }
            .btn-primary {
                color: #fff;
                background-color: #12229D;
                border: 2px solid #12229D;
                font-size: 16px; /* Custom font size */
                transition: background-color 0.3s, border-color 0.3s;
            }
            .btn-primary:hover {
                background-color: #12229D;
                border-color: #12229D;
            }
        </style>
    </head>
    <body>
        <section class="container content-container">
            <h2>Upcoming Appointments</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Appointment Type</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populate with PHP loop for upcoming appointments -->
                        <tr>
                            <td>2024-07-15 02:30 PM</td>
                            <td>Dental Checkup</td>
                            <td>Toothache</td>
                            <td>Confirmed</td>
                        </tr>
                        <!-- Repeat rows as needed -->
                    </tbody>
                </table>
            </div>
        </section>

        
        <script src="" async defer></script>
    </body>
</html>