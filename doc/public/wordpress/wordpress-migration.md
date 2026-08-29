# WordPress Migration

## To local Dev

1.  Backup production databases
1.  Download updated files including
      - wp-content/theme/... if changes
      - wp-content/plugins/... if changes
      - wp-content/uploads
2. Compare and update config files expecially sitmap.xml
3. Edit wordpress database backup scripts, change all instances of production site url to local url
4. Empty local databases and run backup scripts
5. Login admin and go to dashboard
6. Update permalinks if needed
7. Activate plugins as needed
8. Run "Better Search Replace" plugin to update any instances of production site url to local url
