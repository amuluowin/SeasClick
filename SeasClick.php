<?php
$br = (php_sapi_name() == "cli")? "":"<br>";

if(!extension_loaded('SeasClick')) {
	dl('SeasClick.' . PHP_SHLIB_SUFFIX);
}

$config = [
    "host" => "127.0.0.1",
    "compression" => true
    // "port" => "9044",
];

clientTest($config);

function clientTest($config)
{
    $deleteTable = false;
    $client = new SeasClick($config);

    testArray($client, $deleteTable);
    testEnum($client, $deleteTable);
    testString($client, $deleteTable);
    testNullAble($client, $deleteTable);
    testUInt($client, $deleteTable);
    testFloat($client, $deleteTable);
    testUUID($client, $deleteTable);
    testDate($client, $deleteTable);
}

function testArray($client, $deleteTable = false) {
    $client->execute("CREATE TABLE IF NOT EXISTS test.array_test (string_c String, array_c Array(Int8)) ENGINE = Memory");

    $client->insert("test.array_test", [
        'string_c', 'array_c'
    ], [
        [
            'string_c1', [1, 2, 3]
        ],
        [
            'string_c2', [4, 5, 6]
        ]
    ]);

    $client->insert("test.array_test", [
        'string_c'
    ], [
        [
            'string_c1'
        ],
        [
            'string_c2'
        ]
    ]);

    $client->insert("test.array_test", [
        'array_c'
    ], [
        [
            [1, 2, 3]
        ],
        [
            [4, 5, 6]
        ]
    ]);

    $result = $client->select("SELECT {select} FROM {table}", [
        'select' => 'string_c, array_c',
        'table' => 'test.array_test'
    ]);
    print_r($result);

    if ($deleteTable) {
        $client->execute("DROP TABLE {table}", [
            'table' => 'test.array_test'
        ]);
    }
}

function testEnum($client, $deleteTable = false) {
    $client->execute("CREATE TABLE IF NOT EXISTS test.enum_test (enum8_c Enum8('One8' = 1, 'Two8' = 2), enum16_c Enum16('One16' = 1, 'Two16' = 2)) ENGINE = Memory");

    $client->insert("test.enum_test", [
        'enum8_c', 'enum16_c'
    ],[
        [1, 'Two16'],
        ['Two8', 1]
    ]);

    $result = $client->select("SELECT {select} FROM {table}", [
        'select' => 'enum8_c, enum16_c',
        'table' => 'test.enum_test'
    ]);
    print_r($result);

    if ($deleteTable) {
        $client->execute("DROP TABLE {table}", [
            'table' => 'test.enum_test'
        ]);
    }
}

function testString($client, $deleteTable = false) {
    $client->execute("CREATE TABLE IF NOT EXISTS test.string_test (string_c String, fixedstring_c FixedString(50)) ENGINE = Memory");

    $client->insert("test.string_test", [
        'string_c', 'fixedstring_c'
    ], [
        [
            'string_c1',
            'fixedstring_c1'
        ],
        [
            'string_c2',
            'fixedstring_c2'
        ]
    ]);
    
    $result = $client->select("SELECT {select} FROM {table}", [
        'select' => 'string_c, fixedstring_c',
        'table' => 'test.string_test'
    ]);
    print_r($result);
    
    if ($deleteTable) {
        $client->execute("DROP TABLE {table}", [
            'table' => 'test.string_test'
        ]);
    }
}

function testNullAble($client, $deleteTable = false) {
    $client->execute("CREATE TABLE IF NOT EXISTS test.nullable_test (int8null_c Nullable(Int8)) ENGINE = Memory");

    $client->insert("test.nullable_test",[
        'int8null_c'
    ], [
        [null],
        [8]
    ]);

    $result = $client->select("SELECT {select} FROM {table}", [
        'select' => 'int8null_c',
        'table' => 'test.nullable_test'
    ]);
    print_r($result);
    
    if ($deleteTable) {
        $client->execute("DROP TABLE {table}", [
            'table' => 'test.nullable_test'
        ]);
    }
}

function testUInt($client, $deleteTable = false) {
    $client->execute("CREATE TABLE IF NOT EXISTS test.int_test (int8_c Int8, int16_c Int16, uint8_c UInt8, uint16_c UInt16) ENGINE = Memory");

    $client->insert("test.int_test",[
        'int8_c','int16_c','uint8_c','uint16_c'
    ], [
        [8, 8, 8, 8],
        [9, 9, 9, 9],
    ]);

    $client->insert("test.int_test",[
        'int8_c','int16_c','uint8_c'
    ], [
        [8, 8, 8],
        [9, 9, 9],
    ]);
    
    $result = $client->select("SELECT {select} FROM {table}", [
        'select' => 'int8_c, int16_c, uint8_c, uint16_c',
        'table' => 'test.int_test'
    ]);
    print_r($result);
    
    if ($deleteTable) {
        $client->execute("DROP TABLE {table}", [
            'table' => 'test.int_test'
        ]);
    }
}

function testFloat($client, $deleteTable = false) {
    $client->execute("CREATE TABLE IF NOT EXISTS test.float_test (float32_c Float32, float64_c Float64) ENGINE = Memory");

    $client->insert("test.float_test",[
        'float32_c', 'float64_c'
    ], [
        [32.32, 64.64],
        [32.31, 64.68]
    ]);

    $client->insert("test.float_test",[
        'float32_c'
    ], [
        [32.32],
        [32.31]
    ]);
    
    $result = $client->select("SELECT {select} FROM {table}", [
        'select' => 'float32_c, float64_c',
        'table' => 'test.float_test'
    ]);
    print_r($result);
    
    if ($deleteTable) {
        $client->execute("DROP TABLE {table}", [
            'table' => 'test.float_test'
        ]);
    }
}

function testUUID($client, $deleteTable = false) {
    $client->execute("CREATE TABLE IF NOT EXISTS test.uuid_test (uuid_c UUID, uuid2_c UUID) ENGINE = Memory");

    $client->insert("test.uuid_test",[
        'uuid_c', 'uuid2_c'
    ], [
        ['31249a1b-7b05-4270-9f37-c609b48a9bb2', '31249a1b7b0542709f37c609b48a9bb2'],
        ['31249a1b-7b05-4270-9f37-c609b48a9bb2', '31249a1b7b0542709f37c609b48a9bb2'],
    ]);

    $client->insert("test.uuid_test",[
        'uuid_c'
    ], [
        ['00000000-0000-0000-9f37-c609b48a9bb2'],
        ['31249a1b-7b05-4270-9f37-c609b48a9bb2'],
    ]);
    
    $result = $client->select("SELECT {select} FROM {table}", [
        'select' => 'uuid_c, uuid2_c',
        'table' => 'test.uuid_test'
    ]);
    print_r($result);
    
    if ($deleteTable) {
        $client->execute("DROP TABLE {table}", [
            'table' => 'test.uuid_test'
        ]);
    }
}

function testDate($client, $deleteTable = false) {
    $client->execute("CREATE TABLE IF NOT EXISTS test.date_test (date_c Date, datetime_c DateTime) ENGINE = Memory");

    $client->insert("test.date_test", [
        'date_c', 'datetime_c'
    ], [
        [time(), time()],
        [time(), time()]
    ]);
    
    $result = $client->select("SELECT {select} FROM {table}", [
        'select' => 'date_c, datetime_c',
        'table' => 'test.date_test'
    ]);
    print_r($result);
    
    if ($deleteTable) {
        $client->execute("DROP TABLE {table}", [
            'table' => 'test.date_test'
        ]);
    }
}