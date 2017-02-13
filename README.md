t4
==
Цель изменений: добавить возможность явного указания имени связующей таблицы при связи между моделями типа many_to_many. Для этого предлагаю ввести ключевое слово 'pivot' при описании схемы модели (в секции 'relations').

Причина изменений: когда в базе данных применяются схемы и в описании моделей явно указывается название таблицы модели в виде schema_name.table_name, то фреймворк не может правильно вычислить имя связующей таблицы.

Пример использования:
static protected $schema = [
'table' => 'schema_name.table_name',
'columns' => [
'book' => ['type' => 'string'],
],
'relations' => [
'authors' => ['type' => self::MANY_TO_MANY, 'model' => Author::class, 'pivot' =>'schema_name.link_table_name']
]
];