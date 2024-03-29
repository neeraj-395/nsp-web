/* USER PROFILE VIEW TABLE */
CREATE VIEW user_profile AS
    SELECT 
        ud.user_id, ud.name, ud.email,
        ud.contact, ud.profession, uss.github, 
        uss.instagram, uss.twitter, uss.reddit
    FROM 
        user_data ud
    LEFT JOIN 
        user_social_set uss ON ud.user_id = uss.user_id;