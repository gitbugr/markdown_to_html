# Markdown to HTML CLI Tool

**Written by:** Kyron Taylor
**For:** Link Maker Systems

## Usage

Clone this repo. Navigate to the folder containing the `markdown_to_html.php` file, and run the following command:

```
./markdown_to_html.php -i readme.md -o readme.html
```

You can also remove the `-o FILE` argument to write to the buffer.

All of the parsing logic is stored in the includes/markdown_parser.php file, and so small modification of the markdown_to_html.php file would be required for additional storage drivers.

## Note

There is much room for improvement with this script. However, as I was told not to spend too much time on this, I have kept it fairly minimal to demonstrate my approach to this task.

To expand on this, I would have added forward looking for markdown syntax that requires multiple lines, and cleaned up html formatting.

