## 題目一
```sql
SELECT a.bnb_id AS bnb_id, b.name AS bnb_name, SUM(amount) AS may_amount
FROM orders AS a
LEFT JOIN bnbs AS b ON a.bnb_id = b.id 
WHERE 
    currency = 'TWD'
    AND created_at >= 1682870400
    AND created_at < 1685548800
GROUP BY a.bnb_id
ORDER BY may_amount DESC
LIMIT 10
```
## 題目二
#### 可能的瓶頸
1. 時間區段的條件會進行 Full Table Scan，如果這張表是總表效能會非常差
2. orders - currency 沒有設定索引
3. orders - bnb_id 沒有設定索引
#### 可優化的方式
1. 資料表設計依照時間建立表格，ex:
```
...
orders_202404
orders_202405
orders_202406
...
SELECT a.bnb_id AS bnb_id, b.name AS bnb_name, SUM(amount) AS may_amount
FROM orders_202405 AS a
LEFT JOIN bnbs AS b ON a.bnb_id = b.id
WHERE currency = 'TWD'
GROUP BY a.bnb_id
ORDER BY may_amount DESC
LIMIT 10
```
這樣程式碼只需要針對要分析的月份表格進行操作，效能可以大幅提升

2. 根據第一點資料表設計的優化，建立 orders_XXXXXX 表格索引
  * currency
  * bnb_id
  * currency, bnb_id 組合索引

## API 實作測驗

### Docker Build & Run

```shell
$ cd orders
$ docker-compose up --build -d
```

### Install Lumen Dependency
```shell
$ docker-compose exec php sh
$ cd app
$ composer install
```

### Service Online
#### Base URL: http://localhost:80
```
Lumen (10.0.4) (Laravel Components ^10.0)
```
#### API Documentation
##### Create Order
* Method: POST
* Endpoint: `/api/orders`
* Headers:
```
  Content-Type: application/json
```
* Request Body:
```json
{
  "id": "A0000001",
  "name": "Gelody Holiday Inn",
  "address": {
    "city": "taipei-city",
    "district": "da-an-district",
    "street": "fuxing-south-road"
  },
  "price": "1990",
  "currency": "TWD"
}
```
### Unit Test
```shell
$ ./vendor/bin/phpunit
PHPUnit 10.5.38 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.25
Configuration: /var/www/html/app/phpunit.xml

......                                                              6 / 6 (100%)

Time: 00:01.377, Memory: 14.00 MB

OK (6 tests, 8 assertions)
```

## 程式設計
此專案使用了以下幾種設計模式：
#### 1. 介面隔離原則（Interface Segregation）+ 策略模式（Strategy Pattern）
  * OrderValidator 和 OrderTransformer 兩個介面定義了驗證和轉換的抽象行為
  * OrderFormatValidator 與 CurrencyTransformer 個別實作介面方法，有新需求容易新增與替換
#### 2. 依賴注入（Dependency Injection）
  * OrderService 通過建構子注入 OrderValidator 和 OrderTransformer 的實例
  * OrderController 通過建構子注入 OrderService
  * 使用 OrderServiceProvider 進行依賴綁定，統一管理依賴關係
#### 3. 責任鏈模式（Chain of Responsibility）的簡單實現
  * 在 OrderService 的 process 方法中，資料依序經過：
    1. 驗證（Validation）
    2. 轉換（Transform）
  * 每個步驟都可以對資料進行處理，形成一個處理鏈
  
這樣的架構設計具有高內聚低耦合的特性，不僅易於擴展和維護，也方便進行單元測試。
