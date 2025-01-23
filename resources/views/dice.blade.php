<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-button {
            font-size: 1.2rem;
            padding: 10px 20px;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        /* Style for the heading */
        h2 {
            position: relative; /* Make h2 a positioned element */
            font-size: 24px;
            display: inline-block; /* Ensure it wraps properly with content */
        }

        /* Style for the dot */
        .dot {
            position: absolute;
            top: 50%;
            left: -40px; /* Adjust distance from the text */
            transform: translateY(-50%);
            background-color: {{ $color->color }}; /* Change color as needed */
            width: 30px;
            height: 30px;
            border-radius: 50%;
            z-index: -1; /* Place the dot behind the text */
        }

        .hidden-content {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hidden-content .content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        .hidden-content .close-btn {
            margin-top: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .hidden-content .close-btn:hover {
            background-color: #0056b3;
        }

        #imageContainer img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
            margin: auto;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
        Pusher.logToConsole = true;
        var pusher = new Pusher('e8e1eaf7e0922d7d2058', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('player-refresh-channel');
        channel.bind('refresh-event', function(data) {
            if (data == @json($teamNumber) || data == -1) {
                location.reload();
            }
        });

        var channel = pusher.subscribe('player-question-channel');
        channel.bind('question-event', function(data) {
            if (data['message'][2] == @json($teamNumber)) {
                document.getElementById('hiddenContent').style.removeProperty('display');
                document.getElementById('hiddenContent').style.display = 'flex';
                let isImage = data['message'][1];
                if (isImage == 1) {
                    document.getElementById('question').innerText = '';
                    const imageContainer = document.getElementById('imageContainer');
                    const img = document.createElement('img');
                    const imagePath = data['message'][0];
                    img.src = "{{ asset('') }}" + imagePath;
                    imageContainer.appendChild(img);
                } else {
                    document.getElementById('question').innerText = data['message'][0];
                }
            }
        });

        var channel = pusher.subscribe('current-player-channel');
        channel.bind('permission-event', function(data) {
            if (data['message'] == @json($teamNumber)) {
                document.getElementById('generateButton').removeAttribute('disabled');
                document.getElementById('generateButton').textContent = "Your Turn";
                document.getElementById('status').textContent = "Click to roll Dices";
            }
        });
    </script>
</head>
<body>

    <div id="hiddenContent" class="hidden-content" style="{{ $winner ? 'display: flex;' : 'display: none;' }}">
        <div class="content">

            @if ($winner)
                <h1>Big Con Con!!</h1>
                <p id="question" style="font-size: 24px">You Already Won.</p>
                <p>Relax and Enjoy Other Players' Game.</p>
                <div id="imageContainer" style="width: 100%; height: 100%; overflow: hidden;"></div>
            @else
                <h2>Question</h2>
                <p id="question" style="font-size: 24px">Someting wrong! The question should be here...</p>
                <div id="imageContainer" style="width: 100%; height: 100%; overflow: hidden;"></div>
                <br>
                <br>

                <button class="close-btn" style="background-color: rgb(255, 0, 0); color: white;" onclick="closeHiddenTab()">Close</button>
            @endif

        </div>
    </div>

    <div class="container text-center my-5">
        <h1>Gp Number: "{{ $teamNumber }}"</h1>
        <h2>: Color <div class="dot dot1"></div></h2>
        <br>
        <h2>Current Position: {{ $color->pos }}</h2>
        <br>
        <br>

        <h1 id="status" class="mb-4" style="color: red">Wiat for your turn!!</h1>
        <button id="generateButton" class="btn btn-primary custom-button" onclick="rolledDices()" disabled>Wait</button>
        <div id="result" class="mt-4"></div>
        <p id="ans_p" hidden class="alert alert-info">Result: <strong id="ans_num"></strong></p>

    </div>

    <script>
        function rolledDices() {
            document.getElementById('generateButton').disabled = true;
            document.getElementById('generateButton').textContent = "Wait for your turn";
            document.getElementById('status').textContent = "Wait for your turn";
            document.getElementById('ans_p').hidden = false;

            let randomNumber = 0;

            for (let i = 0; i < 20; i++) {
                randomNumber = Math.floor(Math.random() * 6) + 1;
                document.getElementById('ans_num').textContent = randomNumber;
            }

            $.ajax({
                url: "{{ route('postrmNumber') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    gp: @json($teamNumber),
                    randomNumber: randomNumber
                },
                success: function(response) {
                    console.log('Success:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
    </script>

    <script>
        function closeHiddenTab() {
            document.getElementById('hiddenContent').style.display = 'none';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

