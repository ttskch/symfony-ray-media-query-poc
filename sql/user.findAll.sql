SELECT
    u.id,
    u.username,
    json_group_array(
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
    ) as team_histories_json
FROM
    user u
    LEFT JOIN user_team_history uth ON u.id = uth.user_id
    LEFT JOIN team t ON uth.team_id = t.id
GROUP BY
    u.id
