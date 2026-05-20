<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlockType;
use App\Models\BlockInstance;

class SharedBlockSeeder extends Seeder
{
    public function run(): void
    {
        $presets = [
            // ===== ヒーロー・キャンバス (hero_canvas) =====
            'hero_canvas' => [
                [
                    'name' => 'グランドオープン・ヒーロー',
                    'settings' => [
                        'headline' => "GRAND\nOPENING",
                        'lead_text' => '新しいショッピング体験が、今はじまる。厳選されたプロダクトと洗練されたデザインの調和をお楽しみください。',
                        'bg_image' => 'https://images.unsplash.com/photo-1635776062127-d379bfcba9f8?auto=format&fit=crop&q=80&w=2000',
                        'cta_primary_label' => '今すぐ探す',
                        'cta_primary_link' => '/products',
                        'overlay_opacity' => 0.5,
                    ],
                ],
                [
                    'name' => 'シーズナル・セール・ヒーロー',
                    'settings' => [
                        'headline' => "SUMMER\nCOLLECTION",
                        'lead_text' => '最大50%OFF。この夏、あなたのスタイルをアップデートする絶好のチャンス。',
                        'bg_image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&q=80&w=2000',
                        'cta_primary_label' => 'セール会場へ',
                        'cta_primary_link' => '/sale',
                        'overlay_opacity' => 0.6,
                    ],
                ],
                [
                    'name' => 'ブランドストーリー・ヒーロー',
                    'settings' => [
                        'headline' => "OUR\nSTORY",
                        'lead_text' => '品質へのこだわり、デザインへの情熱。私たちのブランドが生まれた背景をご紹介します。',
                        'bg_image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=2000',
                        'cta_primary_label' => 'ブランドについて',
                        'cta_primary_link' => '/about',
                        'overlay_opacity' => 0.55,
                    ],
                ],
                [
                    'name' => '新商品ローンチ・ヒーロー',
                    'settings' => [
                        'headline' => "NEW\nARRIVALS",
                        'lead_text' => '待望の新コレクションが入荷しました。限定アイテムをいち早くチェック。',
                        'bg_image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=2000',
                        'cta_primary_label' => '新商品を見る',
                        'cta_primary_link' => '/new',
                        'overlay_opacity' => 0.45,
                    ],
                ],
                [
                    'name' => 'ミニマル・ヒーロー',
                    'settings' => [
                        'headline' => "LESS IS\nMORE",
                        'lead_text' => 'シンプルで洗練されたライフスタイルを提案します。',
                        'bg_image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&q=80&w=2000',
                        'cta_primary_label' => 'コレクション',
                        'cta_primary_link' => '/collections',
                        'overlay_opacity' => 0.4,
                    ],
                ],
            ],

            // ===== ベントー・グリッド (bento_grid) =====
            'bento_grid' => [
                [
                    'name' => 'サービス紹介グリッド',
                    'settings' => [
                        'title' => '私たちの強み',
                        'subtitle' => 'WHY CHOOSE US',
                        'item_1_title' => '全国送料無料',
                        'item_1_text' => '¥5,000以上のご注文で送料無料。最短翌日にお届けいたします。',
                        'item_1_image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&q=80&w=800',
                        'item_2_title' => '30日間返品保証',
                        'item_2_text' => 'ご満足いただけない場合、30日以内であれば全額返金いたします。',
                        'item_3_title' => '品質保証',
                        'item_4_title' => '24時間サポート',
                    ],
                ],
                [
                    'name' => 'ブランドバリュー・グリッド',
                    'settings' => [
                        'title' => 'ブランドの哲学',
                        'subtitle' => 'OUR VALUES',
                        'item_1_title' => 'サステナビリティ',
                        'item_1_text' => '環境に配慮した素材選びと製造プロセスを徹底しています。',
                        'item_1_image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=800',
                        'item_2_title' => 'クラフトマンシップ',
                        'item_2_text' => '熟練の職人が一つひとつ丁寧に仕上げています。',
                        'item_3_title' => '革新',
                        'item_4_title' => '透明性',
                    ],
                ],
                [
                    'name' => '新機能ハイライト・グリッド',
                    'settings' => [
                        'title' => '最新アップデート',
                        'subtitle' => "WHAT'S NEW",
                        'item_1_title' => 'AIレコメンド',
                        'item_1_text' => 'お客様の好みを学習し、最適な商品をご提案します。',
                        'item_1_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=800',
                        'item_2_title' => 'ワンクリック決済',
                        'item_2_text' => 'お支払い情報を安全に保存。次回からワンクリックで購入完了。',
                        'item_3_title' => 'リアルタイム追跡',
                        'item_4_title' => 'ポイント2倍',
                    ],
                ],
                [
                    'name' => 'カテゴリ案内グリッド',
                    'settings' => [
                        'title' => 'カテゴリから探す',
                        'subtitle' => 'BROWSE BY CATEGORY',
                        'item_1_title' => 'インテリア',
                        'item_1_text' => '暮らしを彩る家具・雑貨コレクション。',
                        'item_1_image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&q=80&w=800',
                        'item_2_title' => 'ファッション',
                        'item_2_text' => 'トレンドを押さえた最旬アイテム。',
                        'item_3_title' => 'ビューティー',
                        'item_4_title' => 'フード',
                    ],
                ],
                [
                    'name' => '実績紹介グリッド',
                    'settings' => [
                        'title' => '数字で見る実績',
                        'subtitle' => 'BY THE NUMBERS',
                        'item_1_title' => '50,000+',
                        'item_1_text' => 'これまでにお届けした商品数。一つひとつに想いを込めて。',
                        'item_1_image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&q=80&w=800',
                        'item_2_title' => '98%',
                        'item_2_text' => 'お客様満足度。リピート率の高さが品質の証です。',
                        'item_3_title' => '47都道府県',
                        'item_4_title' => '創業10年',
                    ],
                ],
            ],

            // ===== スプリット・バナー (split_banner) =====
            'split_banner' => [
                [
                    'name' => '季節キャンペーン・バナー',
                    'settings' => [
                        'headline' => '春の新生活応援セール',
                        'description' => '新生活に必要なアイテムを特別価格でご用意。期間限定のお得なセットもお見逃しなく。',
                        'image_url' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&q=80&w=1200',
                        'button_text' => 'セール会場へ',
                        'button_url' => '/sale',
                        'alignment' => 'left',
                    ],
                ],
                [
                    'name' => '会員登録促進バナー',
                    'settings' => [
                        'headline' => '会員限定特典',
                        'description' => '今すぐ無料会員登録で、初回購入10%OFFクーポンをプレゼント。ポイント還元やセール先行案内も。',
                        'image_url' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&q=80&w=1200',
                        'button_text' => '無料で登録する',
                        'button_url' => '/register',
                        'alignment' => 'right',
                    ],
                ],
                [
                    'name' => 'コラボレーション告知バナー',
                    'settings' => [
                        'headline' => '限定コラボレーション',
                        'description' => '人気デザイナーとの特別なコラボレーションアイテムが登場。数量限定、お早めにどうぞ。',
                        'image_url' => 'https://images.unsplash.com/photo-1542010589005-d1eacc3918f2?auto=format&fit=crop&q=80&w=1200',
                        'button_text' => '詳しく見る',
                        'button_url' => '/collaboration',
                        'alignment' => 'left',
                    ],
                ],
                [
                    'name' => 'ギフトガイド・バナー',
                    'settings' => [
                        'headline' => 'ギフトガイド',
                        'description' => '大切な人への贈り物に迷ったら。予算・シーン別に最適なギフトをご提案します。',
                        'image_url' => 'https://images.unsplash.com/photo-1513885535751-8b9238bd345a?auto=format&fit=crop&q=80&w=1200',
                        'button_text' => 'ギフトを探す',
                        'button_url' => '/gifts',
                        'alignment' => 'right',
                    ],
                ],
                [
                    'name' => 'アプリ案内バナー',
                    'settings' => [
                        'headline' => '公式アプリ',
                        'description' => 'アプリ限定クーポンや、プッシュ通知でセール情報をいち早くお届け。',
                        'image_url' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&q=80&w=1200',
                        'button_text' => 'ダウンロード',
                        'button_url' => '/app',
                        'alignment' => 'left',
                    ],
                ],
            ],

            // ===== プロダクト・グリッド (product_grid) =====
            'product_grid' => [
                [
                    'name' => '新着商品グリッド',
                    'settings' => ['category_id' => null, 'limit' => 8],
                ],
                [
                    'name' => 'おすすめ商品グリッド',
                    'settings' => ['category_id' => null, 'limit' => 6],
                ],
                [
                    'name' => '人気ランキングTOP12',
                    'settings' => ['category_id' => null, 'limit' => 12],
                ],
                [
                    'name' => 'カテゴリ別4商品',
                    'settings' => ['category_id' => 1, 'limit' => 4],
                ],
                [
                    'name' => '大型グリッド（16商品）',
                    'settings' => ['category_id' => null, 'limit' => 16],
                ],
            ],

            // ===== プロダクト・スクローラー (product_scroller) =====
            'product_scroller' => [
                [
                    'name' => '新着コレクション・スクロール',
                    'settings' => ['title' => '新着アイテム', 'category_id' => null, 'limit' => 10],
                ],
                [
                    'name' => 'セール商品スクロール',
                    'settings' => ['title' => 'SALE アイテム', 'category_id' => null, 'limit' => 8],
                ],
                [
                    'name' => 'おすすめスクロール',
                    'settings' => ['title' => 'スタッフのおすすめ', 'category_id' => null, 'limit' => 6],
                ],
                [
                    'name' => 'カテゴリ特集スクロール',
                    'settings' => ['title' => 'ピックアップ', 'category_id' => 1, 'limit' => 8],
                ],
                [
                    'name' => '限定商品スクロール',
                    'settings' => ['title' => '期間限定アイテム', 'category_id' => null, 'limit' => 5],
                ],
            ],

            // ===== フィーチャー・マトリクス (feature_matrix) =====
            'feature_matrix' => [
                [
                    'name' => 'サービス特徴4つ',
                    'settings' => [
                        'title' => '選ばれる理由',
                        'f1_title' => '安心の品質保証', 'f1_desc' => '全商品に品質保証をお付けしています。万が一の不具合にも迅速に対応いたします。',
                        'f2_title' => '最短翌日配送', 'f2_desc' => '14時までのご注文で翌日お届け。お急ぎの方にもご安心いただけます。',
                        'f3_title' => 'ポイント還元', 'f3_desc' => 'お買い物ごとにポイントが貯まり、次回のお買い物でご利用いただけます。',
                        'f4_title' => '専門スタッフ対応', 'f4_desc' => '商品知識豊富なスタッフが、お客様のご質問に丁寧にお答えします。',
                    ],
                ],
                [
                    'name' => 'ブランドの約束',
                    'settings' => [
                        'title' => '4つのお約束',
                        'f1_title' => 'オーガニック素材', 'f1_desc' => '肌に触れるものだからこそ、天然由来の素材にこだわっています。',
                        'f2_title' => 'フェアトレード', 'f2_desc' => '生産者に公正な対価を支払い、持続可能な取引を実現しています。',
                        'f3_title' => 'エコパッケージ', 'f3_desc' => '再生可能な素材のみを使用した環境にやさしい梱包でお届けします。',
                        'f4_title' => 'カーボンオフセット', 'f4_desc' => '配送時のCO2排出量を100%オフセットしています。',
                    ],
                ],
                [
                    'name' => '技術スペック紹介',
                    'settings' => [
                        'title' => 'テクノロジー',
                        'f1_title' => '防水性能 IPX7', 'f1_desc' => '水深1mに30分間浸水しても機能を維持する高い防水性能。',
                        'f2_title' => '超軽量設計', 'f2_desc' => 'わずか120g。長時間の使用でも疲れにくい設計です。',
                        'f3_title' => '急速充電', 'f3_desc' => '15分の充電で3時間使用可能。忙しい毎日をサポートします。',
                        'f4_title' => '5年保証', 'f4_desc' => '自信の表れとして、業界最長クラスの5年保証をお付けしています。',
                    ],
                ],
                [
                    'name' => '導入ステップ',
                    'settings' => [
                        'title' => '簡単4ステップ',
                        'f1_title' => 'STEP 1: アカウント作成', 'f1_desc' => 'メールアドレスだけで簡単に無料登録。SNSアカウントでもOK。',
                        'f2_title' => 'STEP 2: お好みを設定', 'f2_desc' => '好みのジャンルやサイズを登録すると、おすすめ精度がアップします。',
                        'f3_title' => 'STEP 3: 商品を選ぶ', 'f3_desc' => 'お気に入りの商品をカートに入れたら、ワンクリックで購入完了。',
                        'f4_title' => 'STEP 4: お届け', 'f4_desc' => '丁寧に梱包してご指定の場所までお届けいたします。',
                    ],
                ],
                [
                    'name' => '比較表（自社vs他社）',
                    'settings' => [
                        'title' => '他社との違い',
                        'f1_title' => '返品送料 無料', 'f1_desc' => '他社では有料の返品送料を、当店では全額負担いたします。',
                        'f2_title' => '会員ランク制度', 'f2_desc' => '購入回数に応じてランクアップ。最大10%のポイント還元率。',
                        'f3_title' => '専用コンシェルジュ', 'f3_desc' => 'ゴールド会員以上には専任の担当者がつき、お買い物をサポート。',
                        'f4_title' => 'ギフトラッピング無料', 'f4_desc' => '大切な方への贈り物に。上質なラッピングを無料でご用意。',
                    ],
                ],
            ],

            // ===== FAQ アコーディオン (faq_accordion) ===== ※ 多めに8つ
            'faq_accordion' => [
                [
                    'name' => '配送・送料FAQ',
                    'settings' => [
                        'title' => '配送について',
                        'q1' => '送料はいくらですか？', 'a1' => '全国一律550円（税込）です。¥5,000以上のご注文で送料無料となります。',
                        'q2' => '届くまでどれくらいかかりますか？', 'a2' => '通常2〜3営業日でお届けします。14時までのご注文は翌日発送いたします。',
                        'q3' => '届け先を変更できますか？', 'a3' => '発送前であれば変更可能です。マイページの注文履歴からお手続きいただけます。',
                    ],
                ],
                [
                    'name' => '返品・交換FAQ',
                    'settings' => [
                        'title' => '返品・交換について',
                        'q1' => '返品はできますか？', 'a1' => '商品到着後30日以内であれば返品を承ります。未使用・タグ付きの状態に限ります。',
                        'q2' => 'サイズ交換は可能ですか？', 'a2' => '在庫がある場合に限り、同一商品の別サイズへの交換が可能です。交換送料は当社負担です。',
                        'q3' => '不良品が届いた場合は？', 'a3' => '大変申し訳ございません。すぐに交換品をお送りいたします。着払いにてご返送ください。',
                    ],
                ],
                [
                    'name' => 'お支払いFAQ',
                    'settings' => [
                        'title' => 'お支払いについて',
                        'q1' => '利用できる決済方法は？', 'a1' => 'クレジットカード（VISA, Master, JCB, AMEX）、銀行振込、コンビニ払い、電子マネーがご利用いただけます。',
                        'q2' => '分割払いはできますか？', 'a2' => 'クレジットカードでのお支払いに限り、3回・6回・12回の分割払いが可能です。',
                        'q3' => '領収書は発行できますか？', 'a3' => 'はい。マイページの注文履歴から領収書をPDFでダウンロードいただけます。',
                    ],
                ],
                [
                    'name' => 'アカウント・会員FAQ',
                    'settings' => [
                        'title' => 'アカウントについて',
                        'q1' => '会員登録は必要ですか？', 'a1' => 'ゲスト購入も可能ですが、会員登録いただくとポイント付与、購入履歴の確認などの特典がございます。',
                        'q2' => 'パスワードを忘れました', 'a2' => 'ログイン画面の「パスワードを忘れた方」からリセット手続きを行えます。登録メールアドレスにリセットリンクをお送りします。',
                        'q3' => '退会するにはどうすれば？', 'a3' => 'マイページの「アカウント設定」から退会手続きが可能です。退会後、ポイントは失効いたします。',
                    ],
                ],
                [
                    'name' => '商品についてFAQ',
                    'settings' => [
                        'title' => '商品について',
                        'q1' => '商品のサイズ感を教えてください', 'a1' => '各商品ページにサイズガイドを掲載しています。モデル着用画像も参考にしてください。ご不明な点はお気軽にお問い合わせください。',
                        'q2' => '在庫切れ商品は再入荷しますか？', 'a2' => '再入荷予定のある商品には「再入荷通知」ボタンがあります。ご登録いただくと入荷時にメールでお知らせします。',
                        'q3' => '商品の素材やお手入れ方法は？', 'a3' => '各商品ページの「素材・お手入れ」タブに詳細を記載しています。長くお使いいただくためのケア方法もご案内しています。',
                    ],
                ],
                [
                    'name' => 'ポイント・クーポンFAQ',
                    'settings' => [
                        'title' => 'ポイント・クーポンについて',
                        'q1' => 'ポイントの有効期限は？', 'a1' => '最終購入日から1年間です。期間内にお買い物をすると有効期限が延長されます。',
                        'q2' => 'クーポンの使い方を教えてください', 'a2' => 'カートページまたはお支払い画面にクーポンコード入力欄があります。コードを入力して「適用」ボタンを押してください。',
                        'q3' => 'ポイントとクーポンは併用できますか？', 'a3' => 'はい、ポイントとクーポンは同時にお使いいただけます。',
                    ],
                ],
                [
                    'name' => 'ギフト対応FAQ',
                    'settings' => [
                        'title' => 'ギフトについて',
                        'q1' => 'ギフトラッピングは対応していますか？', 'a1' => 'はい。注文時にギフトラッピングオプション（無料）を選択いただけます。のし紙やメッセージカードも承ります。',
                        'q2' => '配送先に金額がわかる書類は入りますか？', 'a2' => 'ギフト設定をONにしていただければ、納品書を同梱せず、金額の記載のない状態でお届けいたします。',
                        'q3' => '複数の届け先に送れますか？', 'a3' => 'はい。注文時に複数の配送先を指定いただけます。それぞれ個別にラッピング・メッセージカードの設定が可能です。',
                    ],
                ],
                [
                    'name' => '法人・大量注文FAQ',
                    'settings' => [
                        'title' => '法人のお客様へ',
                        'q1' => '法人向け割引はありますか？', 'a1' => '10個以上のまとめ買いで法人割引をご用意しています。お問い合わせフォームよりご相談ください。',
                        'q2' => '請求書払いに対応していますか？', 'a2' => '法人登録をいただいたお客様には、月末締め翌月末払いの請求書払いをご利用いただけます。',
                        'q3' => 'ロゴ入れや名入れは可能ですか？', 'a3' => '一部商品でロゴ入れ・名入れに対応しています。最小ロット数や納期についてはお問い合わせください。',
                    ],
                ],
            ],

            // ===== 顧客の声スライダー (testimonial_slider) =====
            'testimonial_slider' => [
                [
                    'name' => '商品レビュー（満足度高）',
                    'settings' => [
                        'title' => 'お客様の声',
                        't1_name' => '田中 美咲',
                        't1_quote' => '品質の高さに驚きました。写真以上に実物が素敵で、毎日愛用しています。梱包も丁寧で、ギフトにもぴったりだと思います。',
                    ],
                ],
                [
                    'name' => '商品レビュー（リピーター）',
                    'settings' => [
                        'title' => 'リピーターの声',
                        't1_name' => '佐藤 健一',
                        't1_quote' => 'もう3回目の購入です。品揃えが豊富で、毎回新しい発見があります。スタッフの対応も丁寧で、安心して購入できます。',
                    ],
                ],
                [
                    'name' => '商品レビュー（配送評価）',
                    'settings' => [
                        'title' => 'お客様の声',
                        't1_name' => '山口 恵子',
                        't1_quote' => '注文した翌日に届きました！急ぎで必要だったので本当に助かりました。配送状況もリアルタイムで確認できて安心でした。',
                    ],
                ],
                [
                    'name' => '商品レビュー（法人利用）',
                    'settings' => [
                        'title' => '法人のお客様の声',
                        't1_name' => '株式会社クリエイト 購買部',
                        't1_quote' => '社員への記念品として利用させていただきました。大量注文にも迅速にご対応いただき、名入れの仕上がりも大変満足しています。',
                    ],
                ],
                [
                    'name' => '商品レビュー（ギフト利用）',
                    'settings' => [
                        'title' => 'ギフト利用のお客様',
                        't1_name' => '木村 陽子',
                        't1_quote' => '母の誕生日プレゼントに購入しました。ラッピングがとても上品で、開けた瞬間の母の笑顔が忘れられません。素敵なサービスをありがとうございます。',
                    ],
                ],
            ],

            // ===== ニュースレター登録 (newsletter_signup) =====
            'newsletter_signup' => [
                [
                    'name' => 'メルマガ登録（標準）',
                    'settings' => [
                        'title' => '最新情報をお届け',
                        'placeholder' => 'メールアドレスを入力',
                    ],
                ],
                [
                    'name' => 'セール通知登録',
                    'settings' => [
                        'title' => 'セール情報を見逃さない',
                        'placeholder' => 'your@email.com',
                    ],
                ],
                [
                    'name' => '会員限定ニュース',
                    'settings' => [
                        'title' => '会員限定の特別オファー',
                        'placeholder' => 'メールアドレス',
                    ],
                ],
                [
                    'name' => '新商品アラート',
                    'settings' => [
                        'title' => '新商品の入荷をいち早くキャッチ',
                        'placeholder' => 'email@example.com',
                    ],
                ],
                [
                    'name' => 'ウィークリーダイジェスト',
                    'settings' => [
                        'title' => '週刊おすすめダイジェスト',
                        'placeholder' => 'メールアドレスを入力してください',
                    ],
                ],
            ],

            // ===== フリーテキスト (static_cms) ===== BlockLibrarySeeder と同じ1件のみ
            'static_cms' => [
                [
                    'name' => 'フリーテキスト/HTML（標準）',
                    'settings' => [
                        'content' => "<h2>見出し</h2>\n<p>ここに本文やHTMLを入力してください。会社概要・規約・お知らせ・埋め込みマップなど、用途に合わせて複製・編集してお使いください。</p>",
                    ],
                ],
            ],
        ];

        foreach ($presets as $typeKey => $blocks) {
            $type = BlockType::where('type_key', $typeKey)->first();
            if (!$type) continue;

            foreach ($blocks as $block) {
                BlockInstance::create([
                    'block_type_id' => $type->id,
                    'name' => $block['name'],
                    'settings' => $block['settings'],
                    'is_active' => true,
                    'is_shared' => true,
                ]);
            }
        }
    }
}
