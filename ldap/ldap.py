import ldap
import json


  
#Url1: ldap://192.168.50.132:389 Url2: ldap://192.168.50.133:389
#cn=GG_BackOfficeEasy_AR,ou=Appl Groups,ou=Central,ou=AR,dc=cencosud,dc=corp


#Resource of code :https://gist.github.com/ibeex/1288159
def check_credentials(username='fdfdf', password='jhgjjhg'):

   """Verifies credentials for username and password.
   Returns None on success or a string describing the error on failure
   # Adapt to your needs
   """
   LDAP_SERVER = 'ldap://192.168.50.132:389'
   # fully qualified AD user name
   LDAP_USERNAME = '%s@spi.com' % username
   # your password
   LDAP_PASSWORD = password
   base_dn = 'DC=cencosud,DC=corp'
   ldap_filter = 'userPrincipalName=%s@spi.com' % username
   attrs = ['memberOf']
   try:
       # build a client
       ldap_client = ldap.initialize(LDAP_SERVER)
       # perform a synchronous bind
       ldap_client.set_option(ldap.OPT_REFERRALS,0)
       ldap_client.simple_bind_s(LDAP_USERNAME, LDAP_PASSWORD)
   except ldap.INVALID_CREDENTIALS:
     #print("wron")
     ldap_client.unbind()
     return 'Wrong username or password'
   except ldap.SERVER_DOWN:
       #print("down")
       return 'AD server not awailable'
   # all is well
   # get all user groups and store it in cerrypy session for future use
   ab = str(ldap_client.search_s(base_dn,ldap.SCOPE_SUBTREE, ldap_filter, attrs)[0][1]['memberOf'])
   #print("ab"+ab)             
   ldap_client.unbind()
   return 'success'
   
if __name__ == "__main__":
    u="chirag"
    p="secred"
    print(check_credentials(u,p)) 