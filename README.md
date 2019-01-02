# Branch Pruner

This tool has been designed to clean up all non active branches. An active branch by default is defined as the following

- A ticket still open in Jira
- No commits on branch which are not in master
- Not in a safe word list (user definable)
- Not master


## Install

Clone project and run `composer install`.

you will need to copy the `.env.example` to `.env` and fill in the correct values for your set up.

## Running

To run from the root directory of the project run `php bin/CleanBranch.php` with the following command line flags specified

```
--project-key=PROJ      # This is the project name for a Bitbucket code base
--project-name=name     # This is the repository name for a Bitbucket code base
--dry-run               # This enables dry run mode, which will just print out the
--remove-unmerged-check # Removes the unmerged check, useful for old test branches
```
