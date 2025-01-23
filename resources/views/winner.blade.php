<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winner Announcement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #6dd5ed, #2193b0);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .winner-container {
            text-align: center;
            padding: 20px 40px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
        }

        .winner-container h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .winner-container p {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .winner-container span {
            font-size: 24px;
            font-weight: bold;
            color: #ffd700; /* Gold color for highlight */
        }

        .button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: bold;
            color: #2193b0;
            background-color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #d4e6f1;
        }
    </style>
</head>
<body>
    <div class="winner-container">
        <form action="{{ route('player.back') }}" method="GET" enctype="multipart/form-data">
            @csrf

            <h1>🏆 Congratulations!</h1>
            @for ($i = 0; $i < count($winners); $i++)
                <p>The winner is: <span id="winner-name">Team {{ $winners[$i]->number }}</span></p>
            @endfor
            <button class="submit button">Go Back</button>
        </form>
    </div>

</body>
</html>
