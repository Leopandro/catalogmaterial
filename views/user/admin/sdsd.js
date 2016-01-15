for (var i = 0; i < x.length; i++) {
    if (
        (x[i]) == ',') 
    	{
        if (
            (x[i + 1] == '}') ||
            (x[i + 1]) == ']'
        ) {
            i++
        }
    } else
        z += x[i];
}