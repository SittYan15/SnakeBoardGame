<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Board Game</title>
    <style>
        body {
            background-image: url('{{ asset('img/snake_board.png') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .dot-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .dot {
            position: absolute;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid black;
        }

        .dot span {
            position: absolute;
            top: 20%;
            left: 35%;
            font-size: 12px;
            color: white;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
        }

        /* Full-screen overlay for hidden content */
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

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;
        var pusher = new Pusher('e8e1eaf7e0922d7d2058', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('main-channel');
        channel.bind('refresh-event', function(data) {
            window.location.reload();
        });

        var channel = pusher.subscribe('main-question-channel');
        channel.bind('question-event', function(data) {
            document.getElementById('hiddenContent').style.removeProperty('display');
            document.getElementById('hiddenContent').style.display = 'flex';

            let isImage = data['message'][4];
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

            document.getElementById('answer').value = data['message'][1]
            document.getElementById('team').textContent += data['message'][2]

            document.getElementById('team-input').value = data['message'][2]
            document.getElementById('position').value = data['message'][3]
        });

        var channel = pusher.subscribe('main-player-channel');
        channel.bind('main-player-event', function(data) {
            document.getElementById('player-info-' + data['message']).style.color = 'red';
        });

    </script>

</head>
<body>

    @for ($i = 0; $i < count($users); $i++)

        @if ($users[$i]->number == $currentPlayer)
            <h2 id="player-info-{{ $users[$i]->number }}" style="
                position: absolute;
                top: {{ 10 + 35 * $i }}px;
                left: 10px;
                margin: 0;
                padding: 5px 10px;
                background-color: rgba(0, 0, 0, 0.1);
                color: red;
                border-radius: 5px;
                font-size: 18px;
                z-index: 1000;">
                Team Number: "{{ $users[$i]->number }}" - Pos: "{{ $users[$i]->pos }}"
            </h2>
        @else
            <h2 id="player-info-{{ $users[$i]->number }}" style="
                position: absolute;
                top: {{ 10 + 35 * $i }}px;
                left: 10px;
                margin: 0;
                padding: 5px 10px;
                background-color: rgba(0, 0, 0, 0.6);
                color: white;
                border-radius: 5px;
                font-size: 18px;
                z-index: 1000;">
                Team Number: "{{ $users[$i]->number }}" - Pos: "{{ $users[$i]->pos }}"
            </h2>
        @endif
    @endfor

    <button onclick="nextplayer()" style="
        text-decoration: none;
        position: absolute;
        top: 10px;
        left: 92%;
        margin: 0;
        padding: 5px 10px;
        background-color: rgb(67, 199, 15);
        color: white;
        border-radius: 5px;
        font-size: 16px;
        z-index: 1000;
    ">
        Next Player
    </button>

    <button onclick="resentAgain()" style="
        text-decoration: none;
        position: absolute;
        top: 50px;
        left: 90%;
        margin: 0;
        padding: 5px 10px;
        background-color: rgb(67, 199, 15);
        color: white;
        border-radius: 5px;
        font-size: 16px;
        z-index: 1000;
    ">
        Resent Permission
    </button>

    <button onclick="resetGame()" style="
    text-decoration: none;
    position: absolute;
    top: 95%;
    left: 92%;
    margin: 0;
    padding: 5px 10px;
    background-color: rgb(255, 0, 0);
    color: white;
    border-radius: 5px;
    font-size: 16px;
    z-index: 1000;
">
    Reset Game
</button>

    <!-- Hidden Content -->
    <div id="hiddenContent" class="hidden-content" style="display: none">
        <div class="content">
            <h1 id="team">Team number: </h1>
            <p id="question" style="font-size: 24px">Someting wrong! The question should be here...</p>
            <div id="imageContainer" style="width: 100%; height: 100%; overflow: hidden;"></div>
            <input type="text" name="team" id="team-input" hidden>
            <input type="text" name="position" id="position" hidden>
            <br>
            <input id="answer" type="password" placeholder="Enter your password" style="font-size: 20px;">
            <button id="toggle-password" type="button">Show</button>
            <br>
            <br>

            <button class="close-btn" style="background-color: rgb(255, 0, 0); color: white;" onclick="wrong()">Wrong</button>
            <button class="close-btn" style="background-color: rgb(0, 255, 0); color: white;" onclick="correct()">Correct</button>
        </div>
    </div>

    <div class="dot-container" id="dot-container"></div>

</body>
</html>

<script>
    const users = @json($users);

    // Function to create dots
    function createDots() {
        const container = document.getElementById('dot-container');

        users.forEach(user => {
            const randomTop = Math.random() * (user.tops - user.tope) + user.tope;
            const randomLeft = Math.random() * (user.lefts - user.lefte) + user.lefte;

            const dot = document.createElement('div');
            dot.classList.add('dot');

            dot.style.top = `${randomTop}%`;
            dot.style.left = `${randomLeft}%`;
            dot.style.backgroundColor = user.color;

            const label = document.createElement('span');
            label.textContent = user.number;
            dot.appendChild(label);

            container.appendChild(dot);
        });
    }

    // Initialize dots after page load
    window.onload = createDots;
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Get references to the input and toggle button
    const passwordInput = document.getElementById('answer');
    const togglePasswordButton = document.getElementById('toggle-password');

    // Add a click event listener to the toggle button
    togglePasswordButton.addEventListener('click', () => {
        // Check the current type of the input
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text'; // Change to text to show the password
            togglePasswordButton.textContent = 'Hide'; // Update button text
        } else {
            passwordInput.type = 'password'; // Change back to password to hide it
            togglePasswordButton.textContent = 'Show'; // Update button text
        }
    });

    function wrong() {
        // document.getElementById('hiddenContent').style.display = 'none';
        let team = document.getElementById('team-input').value;
        let pos = document.getElementById('position').value;

        $.ajax({
            url: "{{ route('question.wrong') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                team: team,
                pos: pos
            }
        });
    }

    function correct() {
        // document.getElementById('hiddenContent').style.display = 'none';
        let team = document.getElementById('team-input').value;
        let pos = document.getElementById('position').value;

        $.ajax({
            url: "{{ route('question.correct') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                team: team,
                pos: pos
            }
        });
    }

    function nextplayer() {
        $.ajax({
            url: "{{ route('player.next') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            }
        });
    }

    function resentAgain() {
        $.ajax({
            url: "{{ route('player.again') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            }
        });
    }

    function resetGame() {
        $.ajax({
            url: "{{ route('player.reset') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            }
        });
    }
</script>
