# Party Cleaner Package
This serves a very simple purpose. When deleting a user in the Neos backend, ElectronicAddress instances are not cleaned up automatically, as
there is an n:m relationship between Party and ElectronicAddress. For many use cases, such as the one we
use in Sandstorm/UserManagement, we assume a 1:1 relationship between Party and ElectronicAddress.
This command cleans up all "orphan" ElectronicAddress instances that are in the DB and have no relatiobship
to any user.

**To clean up all orphan ElectronicAddress instances, run:**

`./flow partycleaner:removeorphanaddresses`

Example output:
```
Removing: foo@example.com
Successfully removed 1 orphan addresses.
```
