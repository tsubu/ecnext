# ECシステム仕様書（最新版）

※ 本仕様書はLaravel + React + Blade構成に加え、
テンプレート横断型ブロックコンポーネント管理および最新Laravel運用方針を含む最終版である。

（既存仕様は前回提示内容を踏襲）


---

## 34. Laravelバージョンおよび運用方針

### 採用バージョン
- Laravel 13（最新安定版）

### 方針
- 最新安定版追従
- 非推奨機能は使用しない
- Service層で分離しアップデート耐性確保

---

## 35. ブロックタイプJSON Schema設計

### 構造
- Block Type
- Block Preset
- Block Instance

### Hero例
```json
{
  "type_key": "hero",
  "schema": {
    "title": {"type":"string"},
    "image": {"type":"object"}
  }
}
```

### ProductGrid例
```json
{
  "type_key": "product_grid",
  "source_type": "category",
  "limit": 8
}
```

### Block Instance
```json
{
  "block_type_key": "hero",
  "settings": {
    "title": "新作"
  }
}
```

### DB
- block_types
- block_instances
- block_presets

---

## まとめ
- Laravel最新版採用
- ブロック横断利用
- JSON schema管理
