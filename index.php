<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Document</title>
    <style>
        * {
            box-sizing: border-box;
            text-align: center;
        }

        .row {
            margin-left: -5px;
            margin-right: -5px;
        }

        .column {
            float: left;
            width: 50%;
            padding: 5px;
        }

        /* Clearfix (clear floats) */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
        }

        th, td {
            text-align: center;
            padding: 16px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<?php
//$data = sendRequest();

/*
 * Тестовые данные
 */
$data = ' [
{
    "id": "1673539483.5430",
    "name": "SIP/106-0000047e",
    "state": "Ringing",
    "caller": {
      "name": "Number 106",
      "number": "106"
    },
    "connected": {
      "name": "908908",
      "number": "8980980"
    },
    "accountcode": "",
    "dialplan": {
      "context": "call-out",
      "exten": "t",
      "priority": 1,
      "app_name": "AppQueue",
      "app_data": "(Outgoing Line)"
    },
    "creationtime": "2023-01-12T21:04:43.734+0500",
    "language": "ru"
  },
  {
    "id": "1673539635.5445",
    "name": "SIP/808080898-0000047f",
    "state": "Up",
    "caller": {
      "name": "99889",
      "number": "97997"
    },
    "connected": {
      "name": "Number 104",
      "number": "104"
    },
    "accountcode": "",
    "dialplan": {
      "context": "call_centr",
      "exten": "t",
      "priority": 3,
      "app_name": "Queue",
      "app_data": "secretary,t"
    },
    "creationtime": "2023-01-12T21:07:15.552+0500",
    "language": "ru"
  }
  ]
';

$array = json_decode($data, true);

$waiting = array();
$talking = array();
foreach ($array as $value) {
    if ($value['state'] === 'Ringing' && $value['dialplan']['app_name'] === 'AppQueue') {
        $waiting[] = $value['connected']['number'];
    }
    if ($value['state'] === 'Up' && $value['dialplan']['app_name'] === 'Queue') {
        $talking[] = [
            'client_number' => $value['caller']['number'],
            'operator_number' => $value['connected']['number']
        ];
    }

}
/**
 * @return bool|string
 */
function sendRequest()
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://jsonplaceholder.typicode.com/posts",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

?>

<h2>Информация об очереди</h2>
<p>Мы за вами следим работайте</p>

<div class="row">
    <div class="column">
        <table>
            <tr>
                <th>В Ожидании: <?php if (is_null($waiting) && empty($waiting)) {
                        $count = 0;
                    } else {
                        $count = count($waiting);
                    }
                    echo $count; ?></th>
            </tr>
            <?php

            foreach ($waiting as $value) {
                echo '<tr>
                <td>' . $value . '</td>
            </tr>';
            }
            ?>
        </table>
    </div>
    <div class="column">
        <table>
            <tr>
                <th>Отвечают: <?php if (is_null($talking) && empty($talking)) {
                        $count = 0;
                    } else {
                        $count = count($talking);
                    }
                    echo $count; ?></th>
                <th>Номер оператора:</th>
            </tr>
            <?php

            foreach ($talking as $value) {
                echo '<tr>
                <td>' . $value['client_number'] . '</td>
                <td>' . $value['operator_number'] . '</td>
            </tr>';

            }
            ?>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>
</html>

