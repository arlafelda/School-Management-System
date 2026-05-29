<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Print Rapot
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>

        @media print {

            .no-print {
                display: none;
            }

            body {
                background: white;
            }

        }

    </style>
</head>

<body>

    @include('reports.pdf')

    <script>

        window.onload = function () {

            window.print();

        };

    </script>

</body>
</html>