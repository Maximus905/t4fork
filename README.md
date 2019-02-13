t4
==
Fixes
1)(default_connection)
изменен метод Model::setConnection($connection)

Если не указан явно соединение для модели, то используется одно соединение с БД для всех моделей
(основанное на параметрах соединения 'default').
Нужно для корректной работа транзакций (если объекты Connection для дефолтного соединения разныеразные для моделей, 
то возникают проблемы когда используются ограницения внешнего ключа и условие NOT NULL. Модели делают save() в разных
 сессиях и видимо поэтому не видят связующие значения). 
 
2) (pivot_tables)
изменен метод TRelations::getRelationLinkName($relation)

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

3) (fixValidateProperty) Для Postgresql, MySQL изменил методы findAllByQuery, findByQuery, innerSet (трейт TStdGetSet).
Метод валидации свойств теперь  не вызывается при выборке объекта из базы.
В методе валидации свойства isNew === false при  выборке объекта из базы.

4) (addMethodsToTreeExtension) Добавлен статический метод метод findAllRoots(). Ищет все элементы у которых parent == null.

5) добавил свойства isUpdated, wasUpdated. isUpdated устанавливается в true, когда isNew == false и успешно отработал валидатор любого свойства объекта. Сбрасывается в false после save(). wasUpdated устанавливается после save(), но перед afterSave() (по ананлогии с isNew wasNew).