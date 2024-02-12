<style>
    * {
        font-family: Arial, sans-serif;
        background-color: #f8f8f8;
        margin: 0;
        padding: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    caption {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        padding: 20px 0;
        color: #f39022;
    }
</style>

<table>
    <caption>Logs for Inspectors - KD LatinFood</caption>
    <thead>
        <tr>
            <th>User</th>
            <th>Action</th>
            <th>Sección</th>
            <th>Date</th>
            <!-- Agrega aquí los encabezados adicionales de la tabla -->
        </tr>
    </thead>
    <tbody>
        @foreach($data as $inspector)
        <tr>
            <td>{{ $inspector->user }}</td>
            <td>{{ $inspector->action }}</td>
            <td>{{ $inspector->seccion }}</td>
            <td>{{ $inspector->created_at }}</td>
            <!-- Agrega aquí las celdas adicionales de la tabla -->
        </tr>
        @endforeach
    </tbody>
</table>