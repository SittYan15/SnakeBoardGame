<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Three Text Inputs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .input-container {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #c10f03;
            background-color: #edd4d4;
            border-color: #e6c3c3;
        }
    </style>
</head>
<body>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1>ထည့်ပေးပါအုံး</h1>
    <form action="{{ route('question.add') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="input-container">
            <label for="input1">Question:</label>
            <input type="text" id="input1" name="question">
        </div>
        <div class="input-container">
            <label for="input2">Answer</label>
            <input type="text" id="input2" name="answer">
        </div>
        <button type="submit">..Add..</button>
    </form>

    <h2>Questions.. Lists</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Questions</th>
                <th>Answers</th>
            </tr>
        </thead>
        <tbody>

            @for ($i = 0; $i < count($questions); $i++)
                <tr>
                    <td>{{ $questions[$i]->id }}</td>
                    <td>{{ $questions[$i]->question }}</td>
                    <td>{{ $questions[$i]->answer }}</td>
                </tr>
            @endfor

        </tbody>
    </table>
</body>
</html>
