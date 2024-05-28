package it.unisa.utility;

import javax.naming.Context;
import javax.naming.InitialContext;
import javax.naming.NamingException;
import javax.servlet.ServletContext;
import javax.servlet.ServletContextEvent;
import javax.servlet.ServletContextListener;
import javax.sql.DataSource;

public class MainContext implements ServletContextListener {
	
	public void contextInitialized(ServletContextEvent event)
	{
		ServletContext context = event.getServletContext();
		System.out.println("Ho ottenuto il contesto");
		DataSource ds = null;
		
		try {
			Context cont = new InitialContext();
			Context evntctx = (Context) cont.lookup("java:comp/env");
			ds = (DataSource) evntctx.lookup("jdbc/raaf_gaming");
			System.out.println("Ho ottenuto la connesione al DataBase");
		}
		catch(NamingException e)
		{
			e.printStackTrace();
		}
		
		context.setAttribute("DataSource", ds);
	}
	
	public void contextDestroyed(ServletContextEvent event)
	{
		System.out.println("Server Chiuso");
	}

}
