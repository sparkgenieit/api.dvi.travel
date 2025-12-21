UPDATE dvi_guide_details SET guide_dob = NULL WHERE CAST(guide_dob AS CHAR) = '0000-00-00';
