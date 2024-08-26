#!/bin/bash

# Define the output file
output_file="output.txt"

# Create or clear the output file
: > $output_file

# Check if the output file was created
if [ ! -f $output_file ]; then
  echo "Failed to create output file."
  exit 1
fi

# List directory structure excluding node_modules, dest, and cutting-room, and append to output file
tree -I 'node_modules|dest|cutting-room' >> $output_file

# Find all .js, .html, .hbs, .json, .css, and package.json files, excluding node_modules, dest, cutting-room,
# package-lock.json, and files with jquery or bootstrap in the filename, and append their content to the output file
find . -type f \( -name "*.js" -o -name "*.html" -o -name "*.hbs" -o -name "*.json"  -o -name "package.json" \) \
    ! -path "./node_modules/*" ! -path "./dest/*" ! -path "./cutting-room/*" \
    ! -name "package-lock.json" ! -name "*jquery*" ! -name "*bootstrap*" | while read file; do
  echo "========== START OF $file ==========" >> $output_file
  cat "$file" >> $output_file
  echo "========== END OF $file ==========" >> $output_file
done

echo "Output successfully written to $output_file"
