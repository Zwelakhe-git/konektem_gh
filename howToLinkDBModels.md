
```plantuml

entity events {
    ...
    user_id: bigint
    ...
}

entity users {
    * id: bigint
    ...
}

events ||--o{ users : "user_id = id"
```