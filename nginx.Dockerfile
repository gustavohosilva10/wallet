# Use an official Nginx runtime as a parent image
FROM nginx:latest

# Remove the default Nginx configuration
RUN rm /etc/nginx/conf.d/default.conf

# Copy your custom Nginx configuration
COPY default.conf /etc/nginx/conf.d/default.conf

# Expose port 80 for web traffic
EXPOSE 80
