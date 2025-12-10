BEGIN
    -- Construct the dynamic query with pagination, filtering, and search logic
    SET @query = CONCAT(
        "SELECT dip.itinerary_plan_ID, ",
        "dip.arrival_location, ",
        "dip.departure_location, ",
        "dip.trip_start_date_and_time, ",
        "dip.trip_end_date_and_time, ",
        "dip.expecting_budget, ",
        "dip.itinerary_quote_ID, ",
        "dip.no_of_routes, ",
        "dip.no_of_days, ",
        "dip.no_of_nights, ",
        "dip.total_adult, ",
        "dip.total_children, ",
        "dip.total_infants, ",
        "dip.itinerary_preference, ",
        "dip.preferred_room_count, ",
        "dip.total_extra_bed, ",
        "dip.status, ",
        "dip.deleted, ",
        "dip.createdon, ",
        "du.roleID, ", 
        "du.staff_id, ", 
        "du.agent_id, ", 
        "du.username, ",
        "s.staff_name, ", 
        "a.agent_name ",
        "FROM dvi_itinerary_plan_details dip ",
        "JOIN dvi_users du ON dip.createdby = du.userID ",
        "LEFT JOIN dvi_staff_details s ON s.staff_id = du.staff_id ", 
        "LEFT JOIN dvi_agent a ON a.agent_ID = du.agent_id ", 
        "WHERE dip.deleted = '0' "
    );

    -- Add search condition if search_value is provided
    IF search_value IS NOT NULL AND search_value <> '' THEN
        SET @query = CONCAT(@query, 
            " AND (dip.arrival_location LIKE '%", search_value, "%' ",
            "OR dip.departure_location LIKE '%", search_value, "%' ",
            "OR s.staff_name LIKE '%", search_value, "%' ",
            "OR a.agent_name LIKE '%", search_value, "%' ",
            "OR dip.itinerary_quote_ID LIKE '%", search_value, "%' ",
            "OR du.username LIKE '%", search_value, "%')"
        );
    END IF;
    
    IF start_date IS NOT NULL AND start_date <> '' THEN
        SET @query = CONCAT(@query, " AND DATE(dip.trip_start_date_and_time) = '", start_date, "'");
    END IF;
    IF end_date IS NOT NULL AND end_date <> '' THEN
        SET @query = CONCAT(@query, " AND DATE(dip.trip_end_date_and_time) = '", end_date, "'");
    END IF;
    IF source_location IS NOT NULL AND source_location <> '' THEN
        SET @query = CONCAT(@query, " AND dip.arrival_location = '", source_location, "'");
    END IF;
    IF destination_location IS NOT NULL AND destination_location <> '' THEN
        SET @query = CONCAT(@query, " AND dip.departure_location = '", destination_location, "'");
    END IF;
    IF filter_agent_id IS NOT NULL AND filter_agent_id <> '' THEN
        SET @query = CONCAT(@query, " AND dip.agent_id = '", filter_agent_id, "'");
    END IF;
    IF filter_staff_id IS NOT NULL AND filter_staff_id <> '' THEN
        SET @query = CONCAT(@query, " AND dip.staff_id = '", filter_staff_id, "'");
    END IF;
   
    


    -- Filter based on staff and agent
    IF input_staff_id > 0 THEN
        -- Include itineraries created by the staff and their agents
        SET @query = CONCAT(@query, 
            " AND (dip.staff_id = ", input_staff_id, 
            " OR (dip.agent_id IN (SELECT agent_ID FROM dvi_agent WHERE travel_expert_id = ", input_staff_id, ")))"
        );
        IF filter_agent_id > 0 THEN
            SET @query = CONCAT(@query, " AND dip.agent_id = ", filter_agent_id);
        END IF;
    ELSEIF input_agent_id > 0 THEN
        -- Include itineraries created by the agent and their staff
        SET @query = CONCAT(@query, 
            " AND (dip.agent_id = ", input_agent_id, 
            " OR (dip.staff_id IN (SELECT staff_id FROM dvi_staff_details WHERE agent_id = ", input_agent_id, ")))"
        );
        IF filter_staff_id > 0 THEN
            SET @query = CONCAT(@query, " AND dip.staff_id = ", filter_staff_id);
        END IF;
    END IF;

    SET @query = CONCAT(@query, " ORDER BY dip.itinerary_plan_ID DESC LIMIT ", input_offset, ", ", input_limit);

    -- Prepare, execute, and deallocate the dynamic query
    PREPARE stmt FROM @query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END