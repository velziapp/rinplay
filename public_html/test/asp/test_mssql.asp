<%@ Language=VBScript%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<%
mes=""
IsSuccess = false

sServer = Trim(Request.Form("txtServer"))
sUser = Trim(Request.Form("txtUser"))
sPassword = Trim(Request.Form("txtPassword"))
    
if Request("__action")="TestDB" then
	TestDB()	
end if

Sub TestDB()

	Err.Clear()
	on error resume next
	Set objConn = Server.CreateObject("ADODB.Connection")
	if len(Err.Description)<>0 then 
		mes = " " & Err.Description & " MSSQL connection can't be established!"
	else
		objConn.ConnectionString = _  
		"DRIVER={SQL Server}"  & _
		";Server=" & sServer & _
		";UID=" & sUser & _ 
		";PWD=" & sPassword
		objConn.Open
		if len(Err.Description)<>0 then 
			mes = " " & Err.Description & " MSSQL connection can't be established!"
		else
			mes = " MSSQL connection succesfull established!"
			IsSuccess = true
		end if
	end if
	Set objConn = Nothing
End sub

Sub Alert(html)
	if IsSuccess then
		Response.Write "<div class='testRelults' id='testSuccessful'><span class='testResult'>Success:</span>" & html & "</div>"
	else
		Response.Write "<div class='testRelults' id='testFailed'><span class='testResult'>Fail:</span>" & html & "</div>"
	end if
End Sub
%>
<html>
<head>
    <title>ASP test page.</title>
    <meta name=vs_targetSchema content="http://schemas.microsoft.com/intellisense/ie5">
    <link rel="stylesheet" type="text/css" href="/css/winxp.blue.css" />
    <link rel="stylesheet" type="text/css" href="/css/tabs.css" />
    <style>
    .hidden{
        display: none;
    }
    </style>
</head>
<body>
    <form id="form1" action="test_mssql.asp?__action=TestDB&tp=<%= rnd(1)*100*timer %>" method="POST" >
        <input id="__action" type="hidden" value="" />
        <!--**<HEADER>*******************************************************************************************************-->
        <div class="headerContainer">
            <div class="pageHeader">
                <a target="_blank" href="http://swsoft.com/en/products/plesk/" title="Plesk&trade;">
                <img src="/img/common/logo.gif" name="logo" height="50" border="0" width="210" title="Plesk&trade;"></a></div>
        </div>
        <!--**</HEADER>******************************************************************************************************-->
        <!--**<CONTENT>******************************************************************************************************-->
        <div class="contentLayout">
            <div class="contentContainer">
                <div class="pageContent">
                    <div class="pathBar">
                        <a href="/index.html">Home</a> &gt;</div>
                    <div class="screenTitle">
                        ASP possibilities test page</div>
                    <br />
                    <div id="screenTabs">
                        <div id="tabs">
					    <ul>
						    <li class="first"><a href="test_mysql.asp"><span>MySQL</span></a></li>
						    <li id="current"><a href="test_mssql.asp"><span>MSSQL</span></a></li>
						    <li><a href="test_msaccess.asp"><span>MS Access</span></a></li>
						    <li class="last"><a href="test_mail.asp"><span>E-Mail</span></a></li>
					    </ul>
				    </div>
                    </div>
                    <!-- MySQL server -->
                    <div class="tabContent">
                        <p>Here you can test the connection possibility with the Microsoft SQL server.</p>
                        <div class="formContainer">
                            <% if len(mes) > 0 then	Alert(mes) end if %>
                            <fieldset>
                                <legend id="LegendName">Test MSSQL Connection</legend>
                                <p>
                                        <table class="formFields" cellspacing="0" width="100%">
                                            <tr>
                                                <td class="name">
                                                    <label id="lblSource" for="txtServer">
                                                        Server</label></td>
                                                <td>
                                                    <input type = text name="txtServer" size = "25" value = "<% Response.Write(sServer) %>"></td>
                                            </tr>
                                            <tr>
                                                <td class="name">
                                                    <label for="txtUser">
                                                        User name</label></td>
                                                <td>
                                                    <input type = text name="txtUser" size="25" value = "<% Response.Write(sUser) %>"></td>
                                            </tr>
                                            <tr>
                                                <td class="name">
                                                    <label for="txtPassword">
                                                        Password</label></td>
                                                <td>
                                                    <input type = password  name="txtPassword" size="25"></td>
                                            </tr>
                                        </table>
                                </p>
                            </fieldset>
                            <div class="buttonsContainer">
                                <div class="commonButton" id="DBTestButton" title="Test">
                                    <button type="submit" name="bname_ok">
                                        Test</button><span>Test</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**</CONTENT>*****************************************************************************************************-->
    </form>
<div class="footerContainer">
	<div class="footDescription">This page is autogenerated by <a target="_blank" href="http://www.swsoft.com/en/products/plesk/">Plesk</a>&trade;</div>
	<div class="poweredBy"><a target="_blank" href="http://www.swsoft.com/en/products/plesk/"><img src="/img/common/pb_plesk.gif" title="Virtuozzo&trade;"/></a></div>
	<div class="poweredBy"><a target="_blank" href="http://www.swsoft.com/en/products/virtuozzo/"><img src="/img/common/pb_virt.gif" title="Plesk&trade;"/></a></div>
</div>
</body>
</html>
