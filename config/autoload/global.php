<?php

declare(strict_types=1);

return [
    'db' => [
        'driver'    => 'Pdo_Pgsql',
        'hostname'  => $_SESSION['db']['hostname'],
        'port'      => $_SESSION['db']['port'],
        'database'  => $_SESSION['db']['database'],
        'username'  => $_SESSION['db']['username'],
        'password'  => $_SESSION['db']['password'],
//         'adapters' => [
//             'AffiliatesDBAdapter' => [
//                 'driver'    => 'Pdo_Pgsql',
//                 'hostname'  => $_SESSION['affiliates']['hostname'],
//                 'port'      => $_SESSION['affiliates']['port'],
//                 'database'  => $_SESSION['affiliates']['database'],
//                 'username'  => $_SESSION['affiliates']['username'],
//                 'password'  => $_SESSION['affiliates']['password'],
//             ],
//         ]
    ]
];
