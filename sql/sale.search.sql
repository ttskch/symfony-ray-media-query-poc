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
                'team_json', json_object(
                    'id', t.id,
                    'name', t.name
                ),
                'from_date', uth.from_date,
                'to_date', uth.to_date
            )
        )
    ) as user_json
FROM
    sale s
    LEFT JOIN user u ON s.user_id = u.id
    LEFT JOIN user_team_history uth ON u.id = uth.user_id
    LEFT JOIN team t ON t.id = uth.team_id
WHERE
    s.date = :date
    AND (:team_id IS NULL OR t.id = :team_id)
    AND (uth.from_date IS NULL OR uth.from_date <= s.date)
    AND (uth.to_date IS NULL OR uth.to_date >= s.date)
GROUP BY
    s.id
