SELECT
    s.id,
    s.date,
    s.amount,
    json_object(
        'id', u.id,
        'username', u.username,
        'team_histories', json_group_array(
            json_object(
                'id', uth.id,
                'user_id', uth.user_id,
                'team_id', uth.team_id,
                'from_date', uth.from_date,
                'to_date', uth.to_date
            )
        )
    ) as user_json
FROM
    sale s
    LEFT JOIN user u ON s.user_id = u.id
    LEFT JOIN user_team_history uth ON u.id = uth.user_id
WHERE
    s.id = :id
