@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Test case to load/filter/export dataset</h1>
                <p>In these case we have two pages. This (home)page and dataSet page.</p>
                <p>On this page I want to introduce about service</p>
                <p>Service divide by 3 sections</p>
                <ul>
                    <li>Loading a CSV file</li>
                    <li>Filters</li>
                    <li>Table</li>
                </ul>

                <h2>Loading a CSV file to create a dataset</h2>

                <p>You can upload external data in .csv format through the user interface.</p>

                <p>Before uploading a CSV file, please perform the following actions:</p>

                <p>Review the format requirements. In CSV must be headers row. In headers row must be assigned required
                    columns:
                    <b>[category firstname lastname email gender birthDate]</b></p>
                <p>Ensure that all column names are present, otherwise the system will give a data loading error.</p>
                <p>When uploading a file, the system temporarily saves it for processing purposes only. After creating
                    the
                    dataset, the system deletes the file.</p>

                <p>On the home tab, click "Create | Dataset" and select "CSV File".</p>
                <p>Click "Choose file", select the file, and click "Open".</p>
                <p>System will start loading file</p>
                <p>The system will begin to upload the file, and the progress bar indicator will display the percentage
                    of
                    data saved.</p>
                <p>After the file is uploaded, the system will begin to process the data and signal it with a list of
                    messages.</p>
                <p>As the data is processed, it will begin to appear below, and you can immediately start working with
                    it.
                    However, it is recommended to wait for the upload to complete, which the system will notify</p>
                <p class="align-content-center">
                    <a href="{{ route('datasets')}}" class="btn btn-primary btn-lg">Go to Datasets</a>
                </p>

                <h2>Filters</h2>
                <p>Filters make a filtrations on table</p>
                <p>To filter you must click to concrete button like this <span
                        class="badge bg-primary">+ any name</span></p>
                <p>To Remove these filter you can click to button like this <span
                        class="badge bg-success">- any name</span></p>
                <h3>Date filtration</h3>
                <p>To filter dates you must select one of present variants age, age range, date of birth</p>
                <p>By default these filter show age filtration.</p>
                <p>After select type of filtration, you must set input(s). In first two variants it is sliders</p>
                <p>In date of birth filter it is date input.</p>
                <p>When you set concrete filter, you must click to button
                    <button class="btn btn-primary">Set</button>
                </p>
                <p>After that filter is apply</p>
                <p>If you want to remove filter, simpy click to button with name of added filter it looks like <span
                        class="badge bg-success" @click="removeFilter">- Age 32</span></p>
                <h3>Table</h3>
                <p>Table shows your dataset, have pagination and additional information about count of records were filtered</p>
                <p>On bottom of table You can see button Export to CSV. When you click it System will start export filtered data.</p>
                <p>When data will exported, on top of page you will see link with new file...</p>
                <p>Thanks for reading</p>
            </div>
        </div>

@endsection

