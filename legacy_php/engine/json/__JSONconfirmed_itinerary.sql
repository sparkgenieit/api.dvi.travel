DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetConfirmedItineraryPlans`(
    IN `input_offset` INT,
    IN `input_limit` INT,
    IN `input_staff_id` INT,
    IN `input_agent_id` INT,
    IN `input_vendor_id` INT,
    IN `filter_agent_id` INT,
    IN `filter_staff_id` INT,
    IN `search_value` VARCHAR(255),
    IN `start_date` DATE,
    IN `end_date` DATE,
    IN `source_location` TEXT,
    IN `destination_location` TEXT,
    IN `logged_user_level` INT
)
BEGIN

SET @query = CONCAT(
    "SELECT dip.itinerary_plan_ID, ",
    "dip.confirmed_itinerary_plan_ID, ",
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
    "dip.itinerary_total_net_payable_amount, ",
    "dip.itinerary_total_paid_amount, ",
    "dip.itinerary_total_balance_amount, ",
    "dip.agent_id AS CIP_AID, ",
    "dip.itinerary_preference, ",
    "dip.preferred_room_count, ",
    "dip.total_extra_bed, ",
    "dip.status, ",
    "dip.deleted, ",
    "dip.createdon, ",
    "(SELECT customer_name FROM dvi_confirmed_itinerary_customer_details dci ",
    "WHERE dci.itinerary_plan_ID = dip.itinerary_plan_ID ",
    "AND dci.primary_customer = '1' ",
    "AND dci.status = '1' ",
    "AND dci.deleted = '0' LIMIT 1) AS primary_customer, ",
    "du.username, ",
    "du.roleID, ",
    "du.staff_id, ",
    "du.agent_id, ",
    "du.username, ",
    "s.staff_name, ",
    "a.agent_name, ",
    "(SELECT COUNT(cnfhv.cnf_itinerary_plan_hotel_voucher_details_ID) ",
    "FROM dvi_confirmed_itinerary_plan_hotel_voucher_details cnfhv ",
    "WHERE cnfhv.itinerary_plan_id = dip.itinerary_plan_ID) AS hotel_voucher_count, ",
    "(SELECT COUNT(cnfvv.cnf_itinerary_plan_vehicle_voucher_details_ID) ",
    "FROM dvi_confirmed_itinerary_plan_vehicle_voucher_details cnfvv ",
    "WHERE cnfvv.itinerary_plan_id = dip.itinerary_plan_ID) AS vehicle_voucher_count ",
    "FROM dvi_confirmed_itinerary_plan_details dip ",
    "JOIN dvi_users du ON dip.createdby = du.userID ",
    "LEFT JOIN dvi_staff_details s ON s.staff_id = du.staff_id ",
    "LEFT JOIN dvi_agent a ON a.agent_ID = du.agent_id ",
    "WHERE dip.deleted = '0' "
);

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

IF input_staff_id > 0 AND logged_user_level != 6 THEN
    SET @query = CONCAT(@query,
    " AND (dip.staff_id = ", input_staff_id,
    " OR dip.agent_id IN (SELECT agent_ID FROM dvi_agent WHERE travel_expert_id = ", input_staff_id, "))"
    );
    IF filter_agent_id > 0 THEN
        SET @query = CONCAT(@query, " AND dip.agent_id = ", filter_agent_id);
    END IF;
ELSEIF input_agent_id > 0 THEN
    SET @query = CONCAT(@query,
    " AND (dip.agent_id = ", input_agent_id,
    " OR dip.staff_id IN (SELECT staff_id FROM dvi_staff_details WHERE agent_id = ", input_agent_id, "))"
    );
    IF filter_staff_id > 0 THEN
        SET @query = CONCAT(@query, " AND dip.staff_id = ", filter_staff_id);
    END IF;
END IF;

IF input_vendor_id IS NOT NULL AND input_vendor_id <> 0 THEN
    SET @query = CONCAT(@query,
    " AND EXISTS ( ",
    "    SELECT 1 ",
    "    FROM dvi_itinerary_plan_vendor_eligible_list vel ",
    "    WHERE vel.itinerary_plan_id = dip.itinerary_plan_ID ",
    "    AND vel.vendor_id = ", input_vendor_id,
    "    AND vel.itineary_plan_assigned_status = 1 ",
    ") "
    );
END IF;

SET @query = CONCAT(@query, " ORDER BY dip.itinerary_plan_ID DESC LIMIT ", input_offset, ", ", input_limit);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
END$$
DELIMITER ;