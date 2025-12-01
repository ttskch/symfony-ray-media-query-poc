SELECT
    s.id AS s_id,
    s.date AS s_date,
    s.amount AS s_amount,
    u.id AS u_id,
    u.username AS u_username,
    uth.id AS uth_id,
    uth.from_date AS uth_from_date,
    uth.to_date AS uth_to_date,
    t.id AS t_id,
    t.name AS t_name
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
