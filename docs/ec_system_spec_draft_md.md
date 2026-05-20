# Laravel + React + Blade 構成 ECシステム仕様書（叩き台）

## 1. 文書概要

### 1-1. 文書名
Laravel + React + Blade 構成 ECシステム 開発仕様書（叩き台）

### 1-2. 目的
本書は、ECCUBE4.3を参考にしつつ、  
**フロント画面を React、管理画面を Blade、バックエンドを Laravel** で構築するECシステムの要件・機能・画面・データ・運用方針を整理するための叩き台である。

### 1-3. 想定するシステム像
- ユーザー向けストアフロントは React によるSPAを基本とする
- 管理画面は Laravel Blade によるサーバーレンダリング中心
- API は Laravel が提供する
- 商品、受注、会員、在庫、販促、コンテンツ、各種設定を一元管理する
- 将来的な外部連携、モール連携、決済追加、分析機能追加を見据えた構成とする

---

## 2. 開発方針

### 2-1. 基本方針
ECCUBE4.3を参考に、EC運営上の標準機能を備えつつ、  
Laravel の保守性と React の柔軟性を活かした構成で実装する。

### 2-2. 技術スタック
- バックエンド: Laravel
- フロントエンド: React
- 管理画面: Blade
- データベース: **MySQL（MariaDBを含む）、PostgreSQL、SQLite3 から選択可能**
- API形式: REST を基本とする
- 認証: Laravel標準認証基盤をベースに実装
- ストレージ: ローカルストレージ / S3互換ストレージ
- 非同期処理: Laravel Queue
- メール送信: SMTP / 外部メールサービス
- キャッシュ: Redis（推奨）

### 2-3. データベース対応方針
- Laravel のマイグレーション機能を利用し、DB差異を吸収しやすい構成とする
- 特定DB依存のSQLを極力避ける
- 本番利用は MySQL（MariaDB含む）または PostgreSQL を推奨する
- SQLite3 は開発・検証・小規模用途を想定する
- DB固有機能を使う場合は Repository / Query Service 層に閉じ込める

### 2-4. 決済システム対応方針
- **決済手段を差し替え可能な構成**とする
- 決済機能はインターフェース化し、各決済会社ごとに実装クラスを分離する
- 例:
  - クレジットカード決済
  - コンビニ決済
  - 銀行振込
  - 代金引換
  - 電子マネー / ウォレット決済
  - 後払い決済
- 初期構成では、決済プロバイダの変更や追加がしやすいように以下を分離する
  - 決済設定
  - 決済リクエスト生成
  - 外部API通信
  - コールバック受信処理
  - 受注反映処理
- 管理画面から有効/無効の切り替えができるようにする
- 将来的に複数決済会社を併用可能な設計とする

---

## 3. システム対象範囲

### 3-1. 対象
- 一般購入者向けフロントECサイト
- 管理者向けバックオフィス
- 注文処理、会員管理、在庫管理、販促管理
- 決済連携、配送管理、メール通知

### 3-2. 対象外（初期）
- 複数店舗在庫の高度引当
- モール一元管理
- POS連携
- サブスク課金
- BtoB専用価格体系
- 高度なAIレコメンド
- 越境ECの本格多言語多通貨対応

---

## 4. 利用者区分

### 4-1. フロント利用者
- ゲスト
- 会員
- 購入者

### 4-2. 管理画面利用者
- システム管理者
- 店舗運営担当
- 受注担当
- 商品登録担当
- 出荷担当
- サポート担当

---

## 4-3. 導入方針の追加: テンプレート横断型ブロックコンポーネント管理
本システムでは、ページ単位のレイアウト管理に加えて、**テンプレートを横断して利用できるブロックタイプのコンポーネント管理システム**を導入する。

### 4-3-1. 目的
- 特定ページ専用の部品ではなく、複数テンプレートで共通利用できる部品群を管理できるようにする
- 運用担当者が、トップページ・特集ページ・LP・固定ページなどをまたいで同じブロックを再利用できるようにする
- デザインと構造の一貫性を保ちながら、更新コストを下げる
- 将来的にテーマやブランド単位でコンポーネント資産を管理できるようにする

### 4-3-2. 基本コンセプト
- ページは「テンプレート」と「ブロック配置情報」により構成する
- ブロックはページ専用ではなく、**共通ブロックタイプ**として管理する
- 各ブロックタイプは複数テンプレートで再利用可能とする
- ブロックごとに schema を持ち、入力可能な設定項目を定義する
- React側では、APIから取得したページ構成JSONをもとに、ブロックタイプに対応するコンポーネントを動的描画する

### 4-3-3. ブロックタイプの考え方
ブロックタイプは「どのページでも使えるUI部品の種類」として定義する。例:
- Hero
- RichText
- ImageBanner
- ProductGrid
- ProductSlider
- CategoryList
- NewsList
- FAQ
- ButtonGroup
- HTMLEmbed
- Video
- Countdown
- CTA

### 4-3-4. 運用単位
- **Block Master**: システム全体で利用可能なブロックタイプ定義
- **Template**: ページ種別ごとの構成ルール
- **Page Layout**: 実際のページごとの配置情報
- **Block Instance**: ページ上に配置された個別ブロック実体

### 4-3-5. 管理機能要件
- ブロックタイプ作成
- ブロックタイプ編集
- ブロックタイプ有効/無効切替
- schema定義管理
- デフォルト設定管理
- 利用可能テンプレート制御
- ページへのブロック追加
- ページ上のブロック並び替え
- ブロック複製
- ブロックのプリセット化
- 下書き保存
- 公開予約
- PC/スマホ表示確認

### 4-3-6. システム設計方針
- ブロックタイプ定義はマスタとして管理する
- ページ側では、ブロックタイプを参照してインスタンスを持つ
- 設定値はJSON形式で保持する
- フロント描画は React のコンポーネント辞書で解決する
- 将来的にテーマ単位の上書きやブロック差し替えに対応可能な構造とする

---

## 5. 機能一覧

### 5-1. フロント機能
1. トップページ表示
2. 商品一覧表示
3. 商品検索
4. カテゴリ検索
5. タグ検索
6. 商品詳細表示
7. 規格選択
8. カート追加
9. カート編集
10. 注文手続き
11. 会員登録
12. ログイン / ログアウト
13. パスワード再発行
14. マイページ
15. 注文履歴確認
16. お届け先管理
17. お気に入り機能
18. お問い合わせ
19. 特定商取引法ページ
20. 利用規約 / プライバシーポリシー表示
21. メールマガジン登録（任意）
22. クーポン適用
23. レビュー投稿（任意）

### 5-2. 管理機能
1. ダッシュボード
2. 商品管理
3. 商品規格管理
4. カテゴリ管理
5. タグ管理
6. 商品画像管理
7. 商品CSV入出力
8. 受注管理
9. 出荷管理
10. 会員管理
11. 会員CSV入出力
12. 問い合わせ管理
13. クーポン管理
14. バナー / お知らせ管理
15. ページ管理
16. **コンポーネント型ページビルダー管理**
17. メールテンプレート管理
18. 支払方法管理
19. 配送方法管理
20. 税率管理
21. 在庫管理
22. 管理ユーザー管理
23. 権限管理
24. 各種マスタ管理
25. システム設定
26. ログ管理
27. 決済プロバイダ設定管理

### 5-3. テンプレート横断型ブロック管理機能
1. ブロックタイプ作成
2. ブロックタイプ編集
3. schema管理
4. デフォルト設定管理
5. 利用テンプレート設定
6. ページへのブロック追加
7. ブロック並び替え
8. 表示/非表示切替
9. 公開期間設定
10. 下書き保存
11. ページ複製
12. テンプレート化
13. プレビュー表示
14. デバイス別プレビュー
15. JSONエクスポート/インポート（任意）
16. ブロックプリセット管理
17. 動的データ連携設定

---

## 6. 画面仕様（概要）

### 6-1. フロント画面

#### A. トップページ
- メインビジュアル
- 新着商品
- おすすめ商品
- カテゴリ導線
- 特集バナー
- お知らせ
- フッター導線

#### B. 商品一覧
- 商品名
- 商品画像
- 価格
- 在庫有無
- 並び順切替
- ページネーション
- 絞り込み
  - カテゴリ
  - 価格帯
  - タグ
  - 在庫有無

#### C. 商品詳細
- 商品名
- 商品コード
- 商品説明
- 複数画像
- 規格選択
- 価格表示
- 在庫表示
- カート追加
- 関連商品
- レビュー（任意）

#### D. カート
- 商品一覧
- 数量変更
- 削除
- 小計
- 送料
- クーポン
- 合計
- 注文へ進む

#### E. 注文
- 購入者情報入力
- 配送先入力
- 支払方法選択
- 配送方法選択
- 注文確認
- 注文完了

#### F. マイページ
- 会員情報確認/編集
- 配送先管理
- 注文履歴
- お気に入り
- パスワード変更

### 6-2. 管理画面

#### A. ダッシュボード
- 売上集計
- 注文件数
- 新規会員数
- 在庫警告
- 未対応問い合わせ
- 最近の受注
- 最近の会員登録

#### B. 商品管理
- 商品一覧
- 商品登録/編集
- 公開/非公開
- 規格・SKU管理
- カテゴリ紐付け
- タグ紐付け
- 商品画像アップロード
- SEO項目入力
- CSV一括登録

#### C. 受注管理
- 受注一覧
- 受注詳細
- ステータス変更
- 入金確認
- 出荷処理
- 送り状番号管理
- 注文メール再送
- 管理メモ

#### D. 会員管理
- 会員一覧
- 会員詳細
- 購入履歴
- ステータス管理
- ポイント管理（任意）
- 会員CSV出力

#### E. コンテンツ管理
- お知らせ管理
- 固定ページ管理
- トップバナー管理
- 特集ページ管理
- テンプレート横断型ブロック管理
- ページテンプレート管理
- ブロックマスタ管理
- ブロックプリセット管理

#### E-2. テンプレート横断型ブロック管理
- ブロックタイプ作成
- ブロックタイプ編集
- schema編集
- 利用可能テンプレート設定
- ブロック追加
- ブロック削除
- ブロック複製
- ブロック並び替え
- デザイン設定
- 表示条件設定
- JSON保存
- プレビュー表示
- デバイス別確認

#### F. 設定系
- 支払方法
- 配送方法
- 送料
- 税率
- 受注ステータス
- メールテンプレート
- サイト基本情報
- 外部サービス設定
- 決済会社設定
- APIキー / シークレット管理

---

## 7. 業務フロー

### 7-1. 購入フロー
1. 商品検索
2. 商品詳細閲覧
3. カート投入
4. 注文情報入力
5. 支払・配送方法選択
6. 注文確認
7. 注文確定
8. 注文完了メール送信
9. 管理画面へ受注生成
10. 決済結果反映
11. 出荷処理
12. 出荷完了メール送信

### 7-2. 商品登録フロー
1. 管理者ログイン
2. 商品登録
3. 規格設定
4. カテゴリ/タグ設定
5. 画像登録
6. 公開設定
7. フロント反映

### 7-3. 出荷フロー
1. 受注確認
2. 決済確認
3. ピッキング
4. 梱包
5. 送り状番号登録
6. 出荷完了
7. 出荷通知メール送信

### 7-4. 決済連携フロー
1. 注文確定
2. 決済方法判定
3. 決済プロバイダ向けリクエスト生成
4. 外部決済画面遷移またはAPI決済実行
5. 決済結果受信
6. 受注ステータス更新
7. 必要に応じてメール通知
8. エラー時は再決済または受注保留処理

---

## 8. データ設計（主要テーブル案）

### 8-1. ユーザー系

#### users
- id
- name
- email
- password
- status
- last_login_at
- created_at
- updated_at

#### user_addresses
- id
- user_id
- postal_code
- prefecture
- city
- address1
- address2
- tel
- recipient_name

### 8-2. 管理者系

#### admins
- id
- name
- email
- password
- role_id
- last_login_at

#### admin_roles
- id
- name
- description

#### admin_role_permissions
- id
- role_id
- permission_key

### 8-3. 商品系

#### products
- id
- product_code
- name
- slug
- description
- short_description
- status
- price_min
- price_max
- stock_type
- published_at
- meta_title
- meta_description
- created_at
- updated_at

#### product_variants
- id
- product_id
- sku
- variant_name
- price
- stock
- weight
- status

#### product_images
- id
- product_id
- file_path
- sort_no
- is_main

#### categories
- id
- parent_id
- name
- slug
- sort_no
- status

#### product_categories
- id
- product_id
- category_id

#### tags
- id
- name
- slug

#### product_tags
- id
- product_id
- tag_id

### 8-4. 受注系

#### orders
- id
- order_no
- user_id
- order_status
- payment_status
- shipping_status
- subtotal
- discount_total
- shipping_fee
- tax_total
- grand_total
- payment_method_id
- shipping_method_id
- payment_provider_id
- ordered_at

#### order_items
- id
- order_id
- product_id
- product_variant_id
- product_name
- sku
- unit_price
- quantity
- line_total
- tax_rate

#### order_addresses
- id
- order_id
- address_type
- postal_code
- prefecture
- city
- address1
- address2
- recipient_name
- tel

#### shipments
- id
- order_id
- shipping_status
- shipped_at
- tracking_no
- delivery_company

### 8-5. 決済/配送系

#### payment_methods
- id
- name
- code
- fee
- status
- sort_no
- provider_type
- config_json

#### payment_providers
- id
- provider_code
- provider_name
- driver_class
- status
- settings_json
- sort_no

#### payment_transactions
- id
- order_id
- payment_provider_id
- payment_method_id
- transaction_no
- external_transaction_id
- amount
- status
- requested_at
- completed_at
- response_payload
- error_message

#### shipping_methods
- id
- name
- code
- fee_calc_type
- status
- sort_no

### 8-6. 販促系

#### coupons
- id
- code
- name
- discount_type
- discount_value
- start_at
- end_at
- usage_limit
- status

#### favorites
- id
- user_id
- product_id

### 8-7. CMS系

#### pages
- id
- title
- slug
- body
- status
- published_at

#### page_layouts
- id
- page_id
- template_id
- schema_version
- layout_json
- published_layout_json
- status
- published_at

#### block_types
- id
- block_type_key
- block_type_name
- component_name
- schema_json
- default_settings_json
- allowed_templates_json
- status

#### block_instances
- id
- page_layout_id
- block_type_id
- instance_key
- sort_no
- settings_json
- data_source_type
- data_source_settings_json
- visibility_rules_json
- is_enabled

#### block_presets
- id
- name
- block_type_id
- preset_key
- settings_json
- status

#### component_masters
- id
- component_type
- component_name
- component_key
- renderer_key
- schema_json
- default_settings_json
- status

#### page_templates
- id
- name
- template_key
- layout_json
- status

#### banners
- id
- title
- image_path
- link_url
- sort_no
- status

#### notices
- id
- title
- body
- published_at
- status

### 8-8. その他

#### mail_templates
- id
- template_key
- subject
- body
- status

#### audit_logs
- id
- actor_type
- actor_id
- action
- target_type
- target_id
- payload
- created_at

---

## 9. 権限設計

### 9-1. 権限種別
- super_admin
- shop_manager
- order_manager
- product_manager
- shipping_manager
- support_staff
- viewer

### 9-2. 制御対象
- 商品閲覧/登録/編集/削除
- 受注閲覧/編集
- 会員閲覧/編集
- 設定変更
- CSV操作
- メール送信
- ログ閲覧
- 管理者管理
- 決済設定変更

---

## 10. API設計方針

### 10-1. API対象
Reactフロント向けに Laravel API を提供する。

### 10-2. 主なAPI例
- `GET /api/products`
- `GET /api/products/{id}`
- `GET /api/categories`
- `GET /api/cart`
- `POST /api/cart/items`
- `PUT /api/cart/items/{id}`
- `DELETE /api/cart/items/{id}`
- `POST /api/checkout/confirm`
- `POST /api/orders`
- `POST /api/auth/login`
- `POST /api/auth/register`
- `GET /api/mypage/orders`
- `GET /api/mypage/profile`
- `PUT /api/mypage/profile`
- `POST /api/payments/execute`
- `POST /api/payments/callback/{provider}`
- `GET /api/payments/status/{orderNo}`

### 10-3. API設計指針
- REST準拠
- 認証が必要なAPIはトークン認証
- 入力バリデーション統一
- エラー形式統一
- フロントで扱いやすいレスポンス構造
- バージョニングを考慮したURI設計

---

## 11. 非機能要件

### 11-1. 性能
- 商品一覧の初回表示速度を重視
- キャッシュ利用
- 画像最適化
- ページネーション前提
- 管理画面一覧の検索高速化

### 11-2. セキュリティ
- CSRF対策
- XSS対策
- SQLインジェクション対策
- 認証/認可の厳格化
- パスワード強度ポリシー
- 管理画面へのIP制限（任意）
- 管理操作監査ログ
- ファイルアップロード制御
- 個人情報の保護
- 重要情報の暗号化
- 決済トークン・APIキーの安全管理
- Webhook署名検証

### 11-3. 可用性
- 障害時のログ保全
- バックアップ
- メール再送設計
- キュー再実行
- 監視通知

### 11-4. 保守性
- 機能単位で分離
- Service / Repository / UseCase 等で責務分割
- フロントと管理画面の責務分離
- マイグレーション管理
- シーディング管理
- テストしやすい構成
- 決済プロバイダごとの疎結合構成

---

## 12. 主要業務ルール

### 12-1. 商品
- 商品は公開状態のみフロント表示
- 規格単位で在庫を持てる
- 在庫0時の表示制御を設定可能
- 販売期間指定を設定可能

### 12-2. 受注
- 注文確定時に受注番号採番
- 受注ステータス履歴を保持
- 注文時点の価格・商品名を `order_items` に保持
- 会員退会後も受注履歴は保持
- 決済結果に応じて受注ステータスを更新する

### 12-3. 会員
- メールアドレス重複不可
- 退会は論理削除を基本
- 購入履歴参照可能
- 複数配送先管理可能

### 12-4. クーポン
- 有効期間チェック
- 利用回数上限チェック
- 最低購入金額条件対応可
- 対象商品/対象カテゴリ限定対応可

### 12-5. 決済
- 決済方式ごとに利用条件を設定可能とする
- 決済エラー時は受注を即時削除せず、保留または失敗状態で保持する
- Webhook受信により入金・売上確定・取消を反映可能とする
- 同一注文に対する重複決済防止を行う
- 決済会社変更時に業務ロジックの影響範囲を最小化する

---

## 13. メール仕様

### 13-1. 自動送信メール
- 会員登録完了
- パスワード再設定
- 注文完了
- 入金確認
- 出荷完了
- 問い合わせ受付
- 問い合わせ返信
- 決済失敗通知（必要に応じて）

### 13-2. テンプレート管理
- 件名管理
- 本文管理
- プレースホルダ差込
- 管理画面から編集可能

---

## 14. CSV運用仕様

### 14-1. 取込対象
- 商品CSV
- 商品規格CSV
- カテゴリCSV
- 会員CSV
- 受注CSV（出力中心）
- 出荷CSV

### 14-2. 要件
- UTF-8対応
- バリデーションエラー行の明示
- 部分成功/部分失敗の扱い明確化
- 取込履歴保存
- テンプレートCSVダウンロード

---

## 15. 将来拡張想定
- 決済手段追加
- 決済会社追加
- ポイント機能
- 定期購入
- レビュー
- レコメンド
- モール連携
- LINE連携
- Instagram/広告連携
- Webhook/API外部公開
- Headless化の強化
- 複数DB対応の維持強化
- テーマ管理機能
- ページテンプレート配布機能
- セクション/ブロックのマーケットプレイス的拡張
- ABテスト対応ページビルダー

---

## 16. 初期リリース範囲（MVP案）

### 16-1. MVP対象
- 会員登録/ログイン
- 商品一覧/詳細
- カート
- 注文
- 管理画面ログイン
- 商品管理
- カテゴリ管理
- 受注管理
- 会員管理
- 支払/配送方法管理
- お知らせ管理
- メール送信
- 基本的なCSV入出力
- 単一または複数の決済プロバイダ切替対応基盤

### 16-2. Phase2候補
- クーポン
- お気に入り
- レビュー
- 高度検索
- 分析ダッシュボード
- ポイント
- 外部連携API
- 決済管理詳細画面
- 複数決済会社同時運用の高度化

---

## 17. 画面遷移（簡易）

### フロント
トップ  
→ 商品一覧  
→ 商品詳細  
→ カート  
→ 注文情報入力  
→ 注文確認  
→ 注文完了  

### 管理
管理ログイン  
→ ダッシュボード  
→ 商品管理  
→ 受注管理  
→ 会員管理  
→ 設定管理  
→ 決済設定管理  

---

## 18. 開発上の補足方針

### 18-1. Laravel側
- Admin / Storefront API の責務を分離
- FormRequestで入力検証を統一
- Policy / Gateで権限制御
- Queueでメール/バッチ非同期化
- Migration / Seeder 必須
- Storage抽象化
- 決済抽象インターフェースを設ける

### 18-2. React側
- 商品一覧/詳細/カート/注文導線をコンポーネント分離
- 状態管理は軽量構成から開始
- SEOが重要なら一部SSR/SSGも検討
- バリデーションはAPI側を正とする

### 18-3. Blade管理画面
- 一覧・検索・登録・編集の標準UI部品化
- テーブルUI統一
- フラッシュメッセージ統一
- 管理画面テンプレート共通化

### 18-4. DB対応の実装指針
- `json` 型などDB差異がある項目はLaravelの抽象化を優先する
- ENUM依存を避け、マスタまたは定数管理を基本とする
- SQLite3 で不足する制約はアプリケーション側でも補完する
- UUID採用の可否は初期段階で決定する

### 18-5. 決済実装の実装指針
- 例: `PaymentGatewayInterface` を定義する
- 決済会社ごとに実装クラスを作成する
- `PaymentManager` で利用中プロバイダを解決する
- コールバック処理はプロバイダ別に分離する
- APIキー等は環境変数または暗号化済み設定として保持する

---

## 19. 課題・未確定事項
- SPAの範囲をどこまで広げるか
- 初期採用する決済方式
- 初期採用する決済会社
- 配送計算ロジックの複雑度
- 税計算単位
- 在庫引当タイミング
- ゲスト購入の可否
- 複数配送対応有無
- ポイント機能有無
- レビュー機能有無
- モバイル最適化優先度
- 本番DBの第一候補を MySQL / PostgreSQL のどちらにするか

---

## 20. まとめ
本仕様書は、ECCUBE4.3の運用思想を踏まえたうえで、  
**Laravelを業務ロジック中核、Reactを購入者向けUI、Bladeを管理画面** として構成するECシステムの初期叩き台である。

また、以下を重要方針とする。
- **データベースは MySQL（MariaDB含む）、PostgreSQL、SQLite3 から選択可能**
- **決済会社や決済方式を柔軟に切り替え・追加できる構成**
- **MVPでは標準的なEC機能を優先し、将来的な拡張に備える**

まずは以下をMVPの中心として確定させる。
- 商品
- 受注
- 会員
- 管理画面
- 決済/配送
- メール
- CSV運用
- DB選択対応の基本設計
- 決済差し替え対応基盤

---

## 21. システムアーキテクチャ設計（案）

### 21-1. 全体構成
本システムは、以下の3層を基本構成とする。

1. **フロント層**
   - React により構築するストアフロント
   - 商品閲覧、カート、注文、マイページ機能を担当
   - Laravel API と通信する

2. **管理層**
   - Blade により構築する管理画面
   - 商品管理、受注管理、会員管理、設定管理を担当
   - Laravel のWebルーティングを利用する

3. **アプリケーション層**
   - Laravel が業務ロジック、API、管理機能、DBアクセスを統括する
   - Queue、Mail、Storage、認証、決済連携もここで制御する

### 21-2. 構成イメージ
- Storefront: React
- Admin: Blade
- Backend API / Web: Laravel
- DB: MySQL（MariaDB含む） / PostgreSQL / SQLite3
- Cache / Session / Queue: Redis（任意）
- File Storage: local / S3互換
- Payment Gateway: 各社API連携
- Mail: SMTP / 外部メールサービス

### 21-3. Laravel責務分離方針
Laravel 側は以下の責務で分離する。

- Controller
  - リクエスト受付
  - バリデーション済みデータの受け渡し
  - レスポンス返却

- Service / UseCase
  - 業務ロジック実行
  - 注文生成
  - 在庫更新
  - 決済連携
  - 会員処理

- Repository / QueryService
  - DBアクセス抽象化
  - 複雑な検索条件の集約
  - DB差異吸収の一部

- Domain相当の責務
  - 注文状態遷移
  - 金額計算
  - クーポン適用判定
  - 決済状態管理

- Infrastructure
  - 外部決済API
  - メール送信
  - ストレージ
  - Webhook受信

---

## 22. ディレクトリ構成案

### 22-1. バックエンド構成例
```text
app/
  Http/
    Controllers/
      Api/
      Admin/
      Web/
    Requests/
      Api/
      Admin/
  Services/
    Product/
    Order/
    Member/
    Payment/
    Shipping/
  Repositories/
  Models/
  Enums/
  Policies/
  Jobs/
  Mail/
  Support/
    Money/
    Tax/
    Cart/
routes/
  api.php
  web.php
resources/
  views/
    admin/
    emails/
frontend/
  src/
    pages/
    components/
    hooks/
    services/
    stores/
database/
  migrations/
  seeders/
config/
```

### 22-2. フロントエンド構成例
```text
frontend/src/
  pages/
    Home/
    ProductList/
    ProductDetail/
    Cart/
    Checkout/
    MyPage/
  components/
    common/
    product/
    cart/
    order/
  services/
    apiClient.ts
    productApi.ts
    authApi.ts
    orderApi.ts
    paymentApi.ts
  hooks/
  utils/
  stores/
  types/
```

---

## 23. ルーティング方針

### 23-1. フロント向け
- React Router を利用して画面遷移を構築する
- SEO要件が高いページについては別途SSRまたはプリレンダリングを検討する

#### 画面ルート例
- `/`
- `/products`
- `/products/:slug`
- `/cart`
- `/checkout`
- `/checkout/confirm`
- `/checkout/complete`
- `/login`
- `/register`
- `/mypage`
- `/mypage/orders`
- `/mypage/profile`

### 23-2. 管理画面向け
- Laravel の `web.php` により管理画面ルートを定義する
- 例:
  - `/admin/login`
  - `/admin`
  - `/admin/products`
  - `/admin/orders`
  - `/admin/customers`
  - `/admin/settings/payments`

### 23-3. API向け
- `api.php` にてバージョン付きルートを検討する
- 例:
  - `/api/v1/products`
  - `/api/v1/cart`
  - `/api/v1/orders`
  - `/api/v1/payments`

---

## 24. 注文・在庫・決済の状態遷移案

### 24-1. 受注ステータス
- pending: 注文受付直後
- awaiting_payment: 入金待ち
- paid: 入金確認済み
- processing: 出荷準備中
- shipped: 出荷済み
- completed: 完了
- cancelled: キャンセル
- failed: 失敗
- refunded: 返金済み

### 24-2. 決済ステータス
- init
- requested
- authorized
- captured
- failed
- cancelled
- refunded
- partially_refunded

### 24-3. 配送ステータス
- unshipped
- preparing
- shipped
- delivered
- returned

### 24-4. 在庫更新タイミング
在庫更新は以下のいずれかを選択できるようにする。

- カート投入時には引き当てない
- 注文確定時に仮引当する
- 決済完了時に確定引当する
- キャンセル時に戻し在庫する

初期は単純化のため、**注文確定時または決済完了時**を選べる設計を推奨する。

---

## 25. 金額計算仕様（案）

### 25-1. 金額構成
- 商品小計
- 値引き額
- クーポン値引き
- 送料
- 決済手数料
- 税額
- 総合計

### 25-2. 税計算
税計算は以下の方式を設定可能とする。

- 外税
- 内税
- 非課税

### 25-3. 端数処理
端数処理方式は設定可能とする。

- 切り捨て
- 四捨五入
- 切り上げ

### 25-4. 税計算単位
- 商品行単位
- 受注明細合計単位
- 受注合計単位

### 25-5. 割引適用順
以下の順序を基本案とする。

1. 商品価格確定
2. セール価格適用
3. 会員割引適用（任意）
4. クーポン適用
5. 送料計算
6. 決済手数料計算
7. 税計算
8. 総額確定

---

## 26. 決済モジュール設計（詳細案）

### 26-1. 設計方針
決済機能は、決済会社ごとの差異を Laravel アプリケーション本体から分離する。

### 26-2. 抽象インターフェース例
```php
interface PaymentGatewayInterface
{
    public function authorize(array $payload): PaymentResult;
    public function capture(array $payload): PaymentResult;
    public function cancel(array $payload): PaymentResult;
    public function refund(array $payload): PaymentResult;
    public function getStatus(array $payload): PaymentResult;
    public function handleWebhook(array $payload, array $headers = []): PaymentWebhookResult;
}
```

### 26-3. 実装クラス例
- `GmoPaymentGateway`
- `PayJpPaymentGateway`
- `StripePaymentGateway`
- `SquarePaymentGateway`
- `OfflineBankTransferGateway`
- `CashOnDeliveryGateway`

### 26-4. 管理機能要件
- 決済会社ごとの利用有無切替
- APIキー登録
- 秘密鍵登録
- テストモード / 本番モード切替
- 利用可能支払方法の紐付け
- エラーログ確認
- コールバックURL確認

### 26-5. 実装上の注意
- APIキーやシークレットは平文保存しない
- ログには機密情報を残さない
- 署名検証が必要なWebhookは必ず検証する
- タイムアウトや再試行設計を行う
- 冪等性キーの利用を考慮する

---

## 27. DB差異対応方針（詳細）

### 27-1. MySQL / MariaDB
- 一般的な本番利用候補
- Laravelとの相性が良く、導入しやすい
- 小〜中規模ECに向く

### 27-2. PostgreSQL
- 厳密なデータ整合性や高度な検索に向く
- 大規模化や分析用途も見据えやすい
- 将来的な柔軟性が高い

### 27-3. SQLite3
- ローカル開発や簡易検証向け
- 本番では高アクセス用途に不向きなケースがある
- 一部制約や挙動差異に注意が必要

### 27-4. 実装指針
- `enum` の利用は極力避ける
- JSONカラム利用時はLaravelのcast機能を活用する
- DB特有の全文検索はオプション扱いにする
- 文字コード/照合順序は初期段階で統一方針を定める

---

## 28. 管理画面の主要一覧項目（案）

### 28-1. 商品一覧
- ID
- 商品コード
- 商品名
- カテゴリ
- 公開状態
- 在庫状態
- 価格帯
- 更新日

### 28-2. 受注一覧
- 受注番号
- 注文日
- 注文者名
- 支払方法
- 決済状態
- 配送状態
- 合計金額
- 受注状態

### 28-3. 会員一覧
- 会員ID
- 氏名
- メールアドレス
- 会員状態
- 購入回数
- 最終購入日
- 登録日

### 28-4. 決済管理一覧
- トランザクション番号
- 受注番号
- 決済会社
- 支払方法
- 金額
- 決済状態
- リクエスト日時
- 完了日時

---

## 29. テスト方針

### 29-1. テスト分類
- 単体テスト
- 機能テスト
- APIテスト
- 管理画面操作テスト
- 決済モック連携テスト
- CSV取込テスト

### 29-2. テスト対象
- 金額計算
- クーポン適用
- 在庫更新
- 注文確定
- 決済成功/失敗
- Webhook反映
- 管理権限制御
- DB切替時の基本動作

### 29-3. テスト方針補足
- 決済の本番API依存テストは最小限とする
- 可能な限りスタブ/モックを用意する
- DBごとの差異はCIで複数DBテストを実施できると望ましい

---

## 30. 運用・保守方針

### 30-1. バックアップ
- DBの定期バックアップ
- 商品画像等のストレージバックアップ
- バックアップのリストア手順書整備

### 30-2. ログ運用
- アプリケーションログ
- 管理操作ログ
- 決済連携ログ
- メール送信ログ
- 例外ログ

### 30-3. 障害対応
- 決済失敗時の確認手順
- 在庫不整合時の補正手順
- メール送信失敗時の再送手順
- CSV取込失敗時の差戻し手順

### 30-4. リリース運用
- migration 実行手順
- seed 初期投入手順
- 環境変数設定手順
- 決済会社切替手順
- メンテナンスモード利用手順

---

## 31. 初期マイルストーン案

### Phase 0: 基盤構築
- Laravel初期構築
- React初期構築
- Blade管理画面テンプレート構築
- 認証基盤
- DB切替対応の確認
- CI/CD雛形作成

### Phase 1: 商品・会員
- 商品一覧/詳細
- カテゴリ
- 会員登録/ログイン
- マイページ基礎
- 管理画面の商品管理

### Phase 2: カート・注文
- カート
- 注文フロー
- 住所管理
- 受注登録
- メール通知

### Phase 3: 決済・出荷
- 決済抽象化基盤
- 1社目決済導入
- Webhook反映
- 出荷管理
- 管理画面の受注運用

### Phase 4: 運用強化
- CSV
- クーポン
- ログ強化
- 監査ログ
- パフォーマンス改善
- 複数決済会社対応

---

## 32. 今後詰めるべき優先事項
- MVPに含める決済方式の確定
- 本番利用DBの第一候補決定
- 税計算ルールの確定
- 在庫引当タイミングの確定
- ゲスト購入可否の確定
- レビュー、ポイント、クーポンの初期範囲決定
- 管理画面の役割権限詳細化
- ページビルダーをMVPに含めるかPhase2にするか確定
- セクション/ブロックの初期提供数の確定
- ページ編集UIをBlade中心にするか、一部React導入するか確定

---

## 33. テンプレート横断型ブロック管理システムの実装方針（補足）

### 33-1. 実装イメージ
- 管理画面でページを編集する
- ページにはテンプレートを割り当てる
- 利用可能なブロックタイプ一覧から、共通ブロックを選んで追加する
- 保存時はページごとのブロック配置情報と設定値をJSON形式で保持する
- フロントではJSONを読み込み、block type に対応する React コンポーネントへマッピングして描画する

### 33-2. 画面編集イメージ
- 左カラム: 利用可能ブロックタイプ一覧
- 中央: ページプレビュー領域
- 右カラム: 選択中ブロックの設定フォーム

### 33-3. 初期導入に向く構成
初期リリースでは、以下のページを対象に導入するのが現実的である。
- トップページ
- 特集ページ
- LP
- 固定ページ

商品詳細やカート、注文画面などの業務画面は、初期は固定実装として維持する。

### 33-4. 技術的な推奨
- 管理画面本体はBladeで維持
- ただしブロック編集UI部分は、操作性向上のために部分的にReactまたは軽量なJS導入を検討
- フロント描画は React コンポーネント辞書で対応
- block type ごとに JSON schema で設定制約を持たせると保守しやすい
- 動的商品一覧やお知らせ一覧などは data source 設定で切り替えられる構成が望ましい

### 33-5. 推奨MVP範囲
MVPで最低限入れるなら以下が妥当。
- ブロックタイプマスタ管理
- 固定ページ作成
- ブロック追加/削除
- 並び替え
- 公開/非公開
- プレビュー
- 5〜8種類程度の標準ブロックタイプ
- テンプレートごとの利用可能ブロック制御

高度なテンプレート配布、テーマ差し替え、ABテスト、複雑な条件分岐はPhase2以降を推奨する。

### 33-6. この方式の利点
- 同じ Hero や CTA を複数ページ種別で再利用できる
- コンポーネント設計が統一しやすい
- デザイン資産を蓄積しやすい
- 将来的に headless CMS 的な拡張へ繋げやすい
- ページ専用実装の乱立を防ぎやすい

