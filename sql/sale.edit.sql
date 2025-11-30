UPDATE
    sale
SET
    date = :date,
    amount = :amount,
    user_id = :user_id
WHERE
    id = :id
