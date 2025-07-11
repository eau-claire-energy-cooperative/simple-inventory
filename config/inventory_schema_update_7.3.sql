--
-- Add parameters to Lifecycle Check Report
--
UPDATE commands SET parameters = "Email Address", description = "Checks configured Lifecycles to see if any applications need to be checked for an update. Use the email field to send the report to a specific address, if left blank all users will receive an email." where id = 10;