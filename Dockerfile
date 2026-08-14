FROM node:22-alpine

WORKDIR /app
COPY package*.json ./
RUN npm ci --omit=dev
COPY . .

# El servicio concreto se indica desde docker-compose.yml.
CMD ["node", "ms-auth/server.js"]
