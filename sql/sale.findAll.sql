SELECT
    s.id,
    s.date,
    s.amount,
    json_object(
        'id', u.id,
        'username', u.username
    ) as user_json
FROM
    sale s
    LEFT JOIN user u ON s.user_id = u.id
