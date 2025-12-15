FROM node:20-alpine

WORKDIR /app

COPY post-frontend/package*.json ./

RUN npm install

COPY post-frontend/ .

EXPOSE 3000

CMD ["npm", "start"]
