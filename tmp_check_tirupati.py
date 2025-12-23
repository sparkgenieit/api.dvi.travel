import mysql.connector

def check_db():
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="dvi_travels"
        )
        cursor = conn.cursor(dictionary=True)
        
        quote_id = "DVI2025125"
        
        # Check plan details
        cursor.execute("SELECT itinerary_plan_ID, itinerary_quote_ID, arrival_location, departure_location FROM dvi_itinerary_plan_details WHERE itinerary_quote_ID = %s", (quote_id,))
        plan = cursor.fetchone()
        print(f"Plan: {plan}")
        
        if plan:
            plan_id = plan['itinerary_plan_ID']
            
            # Check routes
            print("\nRoutes:")
            cursor.execute("SELECT itinerary_route_ID, itinerary_route_date, location_name, next_visiting_location FROM dvi_itinerary_route_details WHERE itinerary_plan_ID = %s AND deleted = 0 ORDER BY itinerary_route_ID ASC", (plan_id,))
            routes = cursor.fetchall()
            for r in routes:
                print(f"Route ID: {r['itinerary_route_ID']}, Date: {r['itinerary_route_date']}, Location: {r['location_name']}, Next: {r['next_visiting_location']}")

            # Check hotels
            print("\nHotels (Group 1):")
            cursor.execute("""
                SELECT h.itinerary_route_id, h.hotel_id, h.itinerary_route_location, m.hotel_name, m.hotel_city 
                FROM dvi_itinerary_plan_hotel_details h
                LEFT JOIN dvi_hotel m ON h.hotel_id = m.hotel_id
                WHERE h.itinerary_plan_id = %s AND h.deleted = 0 AND h.group_type = 1
            """, (plan_id,))
            hotels = cursor.fetchall()
            for h in hotels:
                print(f"Route ID: {h['itinerary_route_id']}, Hotel ID: {h['hotel_id']}, Name: {h['hotel_name']}, City: {h['hotel_city']}, Location in Row: {h['itinerary_route_location']}")

        cursor.close()
        conn.close()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == '__main__':
    check_db()
