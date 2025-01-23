<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grid with Percentage Labels and Background Image</title>
    <style>
        /* Full-screen body setup */
        body {
            margin: 0;
            height: 100vh;
            background-image: url('{{ asset('img/snake_board.png') }}'); /* Path to your background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Container for the grid */
        .grid-container {
            display: grid;
            width: 100%;
            height: 100%;
            grid-template-columns: repeat(10, 1fr); /* 10 columns */
            grid-template-rows: repeat(10, 1fr); /* 10 rows */
            position: relative;
            z-index: 1; /* Ensure grid is on top of the background */
        }

        /* Style for each grid cell */
        .grid-cell {
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.2); /* Light border for the grid */
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.6); /* Semi-transparent background */
        }

        /* Style for the percentage label */
        .label {
            font-size: 10px;
            color: #333;
            text-align: center;
        }

        .dot-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Style for individual dots */
        .dot {
            position: absolute;
            background-color: red; /* You can change this color */
            width: 25px;
            height: 25px;
            border-radius: 50%;
        }

        /* Example dot positions */
        .dot { top: 0%; left: 0%; }

    </style>
</head>
<body>

    <div class="dot"></div>

    <div class="grid-container">
        <!-- Dynamically generated grid cells with percentage labels -->
    </div>

    <script>
        // Get the grid container
        const gridContainer = document.querySelector('.grid-container');

        // Create the grid with percentage labels
        function createGrid() {
            const rows = 10; // Number of rows
            const cols = 10; // Number of columns

            // Loop through rows and columns to create grid cells
            for (let row = 0; row < rows; row++) {
                for (let col = 0; col < cols; col++) {
                    // Create a grid cell
                    const gridCell = document.createElement('div');
                    gridCell.classList.add('grid-cell');

                    // Calculate the percentage positions for top and left
                    const topPercentage = (row / (rows - 1)) * 100;
                    const leftPercentage = (col / (cols - 1)) * 100;

                    // Add a label with percentage info
                    const label = document.createElement('div');
                    label.classList.add('label');
                    label.innerText = `${topPercentage.toFixed(1)}%, ${leftPercentage.toFixed(1)}%`;

                    // Append the label to the grid cell
                    gridCell.appendChild(label);

                    // Append the grid cell to the grid container
                    gridContainer.appendChild(gridCell);
                }
            }
        }

        // Initialize the grid when the page loads
        window.onload = createGrid;
    </script>

</body>
</html>

<script>
    const dot = document.querySelector('.dot');
    dot.style.top = `${@json($top)}%`;
    dot.style.left = `${@json($left)}%`;
</script>
