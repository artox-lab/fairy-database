<?php

return [
    'country' => [
        [
            'country_id' => 1,
            'country' => 'Тилимилитрямдия'
        ],
        [
            'country_id' => 2,
            'country' => 'Страна Дураков'
        ]
    ],
    'city' => [
        [
            'city_id' => 1,
            'city' => 'Петроваловско-Барабаный',
            'country_id' => 1
        ],
        [
            'city_id' => 2,
            'city' => 'Милитрямск',
            'country_id' => 1
        ]
    ],
    'address' => [
        [
            'address_id' => 1,
            'address' => 'ул.Трямского, 15',
            'district' => 'Конфетный',
            'city_id' => 2,
            'phone' => '+375 29 678 87 63'
        ],
        [
            'address_id' => 2,
            'address' => 'ул. Медвежья, 84',
            'district' => 'Медовый',
            'city_id' => 2,
            'phone' => '+375 29 456 32 19'
        ],
        [
            'address_id' => 3,
            'address' => 'ул. Нора на опушке, 3 поворот налево',
            'district' => 'Центральный',
            'city_id' => 2,
            'phone' => '+375 29 456 32 19'
        ],
        [
            'address_id' => 4,
            'address' => 'ул. Мошенников, 1 (за гаражами)',
            'district' => 'Центральный',
            'city_id' => 1,
            'phone' => '+375 29 555 55 55'
        ],
        [
            'address_id' => 5,
            'address' => 'ул. Поле Чудес, 1 (большой воздушный шарик над зданием, чтобы вы его точно не пропустили, найдя кидаем монетку, закрываем глаза, весело поём, открываем глаза забираем кэш)',
            'district' => 'Большая дорога',
            'city_id' => 1,
            'phone' => '+375 29 555 55 55'
        ]
    ],
    'store' => [
        [
            'store_id' => 1,
            'manager_staff_id' => null,
            'address_id' => 5
        ],
        [
            'store_id' => 2,
            'manager_staff_id' => null,
            'address_id' => 3
        ],
        [
            'store_id' => 3,
            'manager_staff_id' => null,
            'address_id' => 4
        ],
        [
            'store_id' => 4,
            'manager_staff_id' => null,
            'address_id' => 1
        ],
        [
            'store_id' => 5,
            'manager_staff_id' => null,
            'address_id' => 2
        ]
    ],
    'staff' => [
        [
            'staff_id' => 1,
            'first_name' => 'Базилио',
            'last_name' => 'Кот',
            'address_id' => 4,
            'store_id' => 1,
            'username' => 'most_honest_and_beautiful@cat.com'
        ],
        [
            'staff_id' => 2,
            'first_name' => 'Алиса',
            'last_name' => 'Лиса',
            'address_id' => 5,
            'store_id' => 1,
            'username' => 'the_smartest1975@fox.com'
        ],
    ]
];