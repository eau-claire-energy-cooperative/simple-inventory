
<h2>/api/inventory</h2>

<h3>Action: exists</h3>
<p>Checks if a computer exists in the inventory system. If it does all of the known hardware information is returned</p>

<p>Parameters</p>
<ul>
	<li>computer - computer name</li>
</ul>

<p></p>

<h2>/api/log</h2>
<p>This endpoint can only add log information to the database</p>

<p>Parameters</p>
<ul>
	<li>logger - name of module logging the event</li>
	<li>level - level of logged event</li>
	<li>message - message to log</li>
</ul>

<h2>/api/settings</h2>

<h3>Action: get (default)</h3>
<p>Returns all the settings as key/value pairs. </p>

<h2>/api/services</h2>

<h3>Action: get</h3>
<p>Get all of the services information for the given computer id</p>

<p>Parameters</p>
<ul>
	<li>id - the id of the computer in the system </li>
</ul>

